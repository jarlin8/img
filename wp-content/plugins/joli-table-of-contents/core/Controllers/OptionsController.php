<?php

/**
 * @package jolitoc
 */

namespace WPJoli\JoliTOC\Controllers;


class OptionsController
{
    protected $prefix;

    public function __construct()
    {
        $this->prefix = JTOC()::SLUG . '_';
    }

    public function get($option, $default = false)
    {
        return get_option($this->prefix . $option, $default);
    }

    public function set($option, $value, $autoload = null)
    {
        return update_option( $this->prefix . $option, $value, $autoload);
    }

    public function delete($option)
    {
        delete_option( $this->prefix . $option);
    }
}
