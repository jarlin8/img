<?php

namespace AAWP\ActivityLogs;

use Iterator;
use Countable;

/**
 * Class Logs.
 *
 * @since 3.19
 */
class Logs implements Countable, Iterator {

	/**
	 * Iterator position.
	 *
	 * @since 3.19
	 *
	 * @var int
	 */
	private $iterator_position = 0;

	/**
	 * List of log logs.
	 *
	 * @since 3.19
	 *
	 * @var array
	 */
	private $list = [];

	/**
	 * Return the current element.
	 *
	 * @since 3.19
	 *
	 * @return \AAWP\ActivityLogs\Log|null Return null when no items in collection.
	 */
	public function current() {

		return $this->valid() ? $this->list[ $this->iterator_position ] : null;
	}

	/**
	 * Move forward to next element.
	 *
	 * @since 3.19
	 */
	public function next() {

		++ $this->iterator_position;
	}

	/**
	 * Return the key of the current element.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function key() {

		return $this->iterator_position;
	}

	/**
	 * Checks if current position is valid.
	 *
	 * @since 3.19
	 *
	 * @return bool
	 */
	public function valid() {

		return isset( $this->list[ $this->iterator_position ] );
	}

	/**
	 * Rewind the Iterator to the first element.
	 *
	 * @since 3.19
	 */
	public function rewind() {

		$this->iterator_position = 0;
	}

	/**
	 * Count number of Log in a Queue.
	 *
	 * @since 3.19
	 *
	 * @return int
	 */
	public function count() {

		return count( $this->list );
	}

	/**
	 * Push log to list.
	 *
	 * @since 3.19
	 *
	 * @param \AAWP\ActivityLogs\Log $log Log.
	 */
	public function push( $log ) {

		if ( ! is_a( $log, '\AAWP\ActivityLogs\Log' ) ) {
			return;
		}
		$this->list[] = $log;
	}

	/**
	 * Clear collection.
	 *
	 * @since 3.19
	 */
	public function clear() {

		$this->list              = [];
		$this->iterator_position = 0;
	}
}
