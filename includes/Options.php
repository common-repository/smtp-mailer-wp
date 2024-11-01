<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Manager
 *
 * @package Krut1SmtpMailerWp
 */
class Options
{
    /**
     * @var Options
     */
    private static $instance;

    /**
     * @var array
     */
    private $options;

    public $optionName = 'krut1_smtp_mailer_options';

    /**
     * @return Options
     */
    public static function getInstance(): Options
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        $this->loadOptions();
    }

    /**
     * Load options from DB
     */
    public function loadOptions(): void
    {
        $this->options = Plugin::isNetworkActive() ? \get_site_option($this->optionName, []) : \get_option($this->optionName, []);
    }

    /**
     * Get option by name
     *
     * @param      $optionName
     * @param null $default
     * @return mixed|null
     */
    public function getOption($optionName, $default = null)
    {
        return $this->options[$optionName] ?? $default;
    }

    /**
     * Get all options
     *
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Save new options in DB
     *
     * @param null|array $options
     * @return bool
     */
    public function saveOptions($options = null): bool
    {
        if ($options !== null) {
            $this->options = $options;
        }

        if (Plugin::isNetworkActive()) {
            return \update_site_option($this->optionName, $this->options);
        }

        return \update_option($this->optionName, $this->options);
    }

    /**
     * Save one option in DB
     *
     * @param string $optionName
     * @param        $optionValue
     * @return bool
     */
    public function saveOption(string $optionName, $optionValue): bool
    {
        $this->options[$optionName] = $optionValue;

        return $this->saveOptions();
    }
}
