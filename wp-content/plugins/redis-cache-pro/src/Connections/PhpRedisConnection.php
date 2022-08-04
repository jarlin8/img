<?php
/**
 * Copyright © Rhubarb Tech Inc. All Rights Reserved.
 *
 * All information contained herein is, and remains the property of Rhubarb Tech Incorporated.
 * The intellectual and technical concepts contained herein are proprietary to Rhubarb Tech Incorporated and
 * are protected by trade secret or copyright law. Dissemination and modification of this information or
 * reproduction of this material is strictly forbidden unless prior written permission is obtained from
 * Rhubarb Tech Incorporated.
 *
 * You should have received a copy of the `LICENSE` with this file. If not, please visit:
 * https://tyubar.com
 */

declare(strict_types=1);

namespace RedisCachePro\Connections;

use Redis;
use Throwable;

use RedisCachePro\Configuration\Configuration;
use RedisCachePro\Exceptions\ConnectionException;

/**
 * @mixin \Redis
 */
class PhpRedisConnection extends Connection implements ConnectionInterface
{
    /**
     * The Redis client/cluster.
     *
     * @var \Redis|\RedisCluster
     */
    protected $client;

    /**
     * Create a new PhpRedis instance connection.
     *
     * @param  \Redis  $client
     * @param  \RedisCachePro\Configuration\Configuration  $config
     */
    public function __construct(Redis $client, Configuration $config)
    {
        $this->client = $client;
        $this->config = $config;

        $this->log = $this->config->logger;

        $this->setBackoff();
        $this->setSerializer();
        $this->setCompression();
    }

    /**
     * Set the connection's retries and backoff algorithm.
     *
     * @see https://aws.amazon.com/blogs/architecture/exponential-backoff-and-jitter/
     */
    protected function setBackoff()
    {
        if (version_compare(phpversion('redis'), '5.3.5', '<')) {
            return;
        }

        if ($this->config->retries) {
            $this->client->setOption(Redis::OPT_MAX_RETRIES, $this->config->retries);
        }

        if ($this->config->backoff === Configuration::BACKOFF_DEFAULT) {
            $this->client->setOption(Redis::OPT_BACKOFF_ALGORITHM, Redis::BACKOFF_ALGORITHM_DECORRELATED_JITTER);
            $this->client->setOption(Redis::OPT_BACKOFF_BASE, 500);
            $this->client->setOption(Redis::OPT_BACKOFF_CAP, 750);
        }
    }

    /**
     * Set the connection's serializer.
     */
    protected function setSerializer()
    {
        if ($this->config->serializer === Configuration::SERIALIZER_PHP) {
            $this->client->setOption($this->client::OPT_SERIALIZER, (string) $this->client::SERIALIZER_PHP);
        }

        if ($this->config->serializer === Configuration::SERIALIZER_IGBINARY) {
            $this->client->setOption($this->client::OPT_SERIALIZER, (string) $this->client::SERIALIZER_IGBINARY);
        }
    }

    /**
     * Set the connection's compression algorithm.
     */
    protected function setCompression()
    {
        if ($this->config->compression === Configuration::COMPRESSION_NONE) {
            $this->client->setOption($this->client::OPT_COMPRESSION, (string) $this->client::COMPRESSION_NONE);
        }

        if ($this->config->compression === Configuration::COMPRESSION_LZF) {
            $this->client->setOption($this->client::OPT_COMPRESSION, (string) $this->client::COMPRESSION_LZF);
        }

        if ($this->config->compression === Configuration::COMPRESSION_ZSTD) {
            $this->client->setOption($this->client::OPT_COMPRESSION, (string) $this->client::COMPRESSION_ZSTD);
        }

        if ($this->config->compression === Configuration::COMPRESSION_LZ4) {
            $this->client->setOption($this->client::OPT_COMPRESSION, (string) $this->client::COMPRESSION_LZ4);
        }
    }

    /**
     * Execute the callback without data mutations on the connection,
     * such as serialization and compression algorithms.
     *
     * @param  callable  $callback
     * @return mixed
     */
    public function withoutMutations(callable $callback)
    {
        $this->client->setOption($this->client::OPT_SERIALIZER, (string) $this->client::SERIALIZER_NONE);
        $this->client->setOption($this->client::OPT_COMPRESSION, (string) $this->client::COMPRESSION_NONE);

        $result = $callback($this);

        $this->setSerializer();
        $this->setCompression();

        return $result;
    }

    /**
     * Flush the selected Redis database.
     *
     * When asynchronous flushing is not used the connection’s read timeout (if present)
     * is disabled to avoid a timeout and restores the timeout afterwards,
     * even in the event of an exception.
     *
     * @param  bool|null  $async
     * @return true
     */
    public function flushdb($async = null)
    {
        $useAsync = $async ?? $this->config->async_flush;
        $readTimeout = $this->config->read_timeout;

        if ($readTimeout && ! $useAsync) {
            $this->client->setOption(Redis::OPT_READ_TIMEOUT, (string) -1);
        }

        try {
            $useAsync
                ? $this->command('flushdb', [true])
                : $this->command('flushdb');
        } catch (Throwable $exception) {
            throw $exception;
        } finally {
            if ($readTimeout && ! $useAsync) {
                $this->client->setOption(Redis::OPT_READ_TIMEOUT, (string) $readTimeout);
            }
        }

        return true;
    }

    /**
     * Hijack `pipeline()` calls to allow command logging.
     *
     * @return object
     */
    public function pipeline()
    {
        return new Transaction(Redis::PIPELINE, $this);
    }

    /**
     * Hijack `multi()` calls to allow command logging.
     *
     * @param  int  $type
     * @return object
     */
    public function multi(int $type = Redis::MULTI)
    {
        return new Transaction($type, $this);
    }

    /**
     * Send `scan()` calls directly to the client.
     *
     * @param  int  $iterator
     * @param  string  $pattern
     * @param  int  $count
     * @return array|false
     */
    public function scan(?int &$iterator, ?string $pattern = null, int $count = 0) // phpcs:ignore PHPCompatibility
    {
        return $this->client->scan($iterator, $pattern, $count);
    }

    /**
     * Hijack `restore()` calls due to a bug in modern PhpRedis versions
     * when data mutations like compression are used.
     *
     * @param  string  $key
     * @param  int  $timeout
     * @param  string  $value
     * @return bool
     */
    public function restore(string $key, int $timeout, string $value)
    {
        return $this->command('rawCommand', ['RESTORE', $key, $timeout, $value]);
    }

    /**
     * Execute hijacked MULTI transaction/pipeline.
     *
     * This mimics `Connection::command()`.
     *
     * @param  int  $type
     * @param  \RedisCachePro\Connections\Transaction  $tx
     * @return array
     */
    public function executeMulti(int $type, Transaction $tx)
    {
        $method = \str_replace([
            Redis::MULTI,
            Redis::PIPELINE,
        ], [
            'multi',
            'pipeline',
        ], (string) $type);

        $context = [
            'command' => \strtoupper($method),
            'parameters' => [],
        ];

        if ($this->config->debug || $this->config->save_commands) {
            $context['backtrace'] = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);

            if (\function_exists('wp_debug_backtrace_summary')) {
                $context['backtrace_summary'] = \wp_debug_backtrace_summary(__CLASS__);
            }
        }

        try {
            $start = $this->now();

            $pipe = $this->client->{$method}();

            foreach ($tx->commands as $command) {
                $pipe->{$command[0]}(...$command[1]);

                $context['parameters'][] = \array_merge([\strtoupper($command[0])], $command[1]);
            }

            $results = $pipe->exec();

            $time = $this->now() - $start;
            $this->ioWait[] = $time;
        } catch (Throwable $exception) {
            $this->log->error('Failed to execute transaction', $context + [
                'exception' => $exception,
            ]);

            throw ConnectionException::from($exception);
        }

        $ms = \round($time * 1000, 4);

        $this->log->info("Executed transaction in {$ms}ms", $context + [
            'result' => $results,
            'time' => $ms,
        ]);

        return $results;
    }
}
