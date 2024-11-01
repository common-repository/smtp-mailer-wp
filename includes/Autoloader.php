<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Autoloader
 *
 * @package Krut1SmtpMailerWp
 */
class Autoloader
{
    /**
     * Run autoloader.
     *
     * Register a function as `__autoload()` implementation.
     *
     * @since  1.6.0
     * @access public
     * @static
     */
    public static function run(): void
    {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /**
     * Autoload.
     *
     * For a given class, check if it exist and load it.
     *
     * @since  1.6.0
     * @access private
     * @static
     *
     * @param string $class Class name.
     */
    private static function autoload($class): void
    {
        $realClassName = str_replace(__NAMESPACE__ . '\\', '', $class);

        $filePath = KRUT1_SMTP_MAILER_WP_PATH . 'includes/' . $realClassName . '.php';

        if (file_exists($filePath) && is_readable($filePath)) {
            require_once $filePath;
        }
    }
}
