<?php

namespace XHGui;

/**
 * Loads and reads config file.
 */
class Config
{
    private static $config = [];

    public static function boot(): array
    {
        // @deprecated
        // define XHGUI_ROOT_DIR constant, config files may use it
        if (!defined('XHGUI_ROOT_DIR')) {
            define('XHGUI_ROOT_DIR', dirname(__DIR__));
        }

        $configDir = XHGUI_ROOT_DIR . '/config';
        self::load($configDir . '/config.default.php');

        if (file_exists($configDir . '/config.php')) {
            self::load($configDir . '/config.php');
        }

        return self::all();
    }

    /**
     * Load a config file, it will replace
     * all the currently loaded configuration.
     */
    public static function load($file): void
    {
        $config = include $file;
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Get all the configuration options.
     */
    public static function all(): array
    {
        return self::$config;
    }
}
