<?php

namespace ContentEgg\application\libs\scrap;

defined('\ABSPATH') || exit;

/**
 * ScrapArrayConfig class file
 *
 * Minimal config object over a plain array, for callers that cannot use a
 * full CE Config singleton: tests, the deprecated AmazonClient token setters,
 * and AmazonNoApiConfig::options() (which runs before option_values exists).
 *
 * @author keywordrush.com <support@keywordrush.com>
 * @link https://www.keywordrush.com
 * @copyright Copyright &copy; 2026 keywordrush.com
 */
class ScrapArrayConfig
{
    /** @var array */
    protected $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function option($opt_name, $default = null)
    {
        if (!isset($this->values[$opt_name]))
        {
            return $default;
        }

        return $this->values[$opt_name];
    }
}
