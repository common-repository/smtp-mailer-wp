<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Plugin
 *
 * @package Krut1SmtpMailerWp
 */
class Plugin
{
    /**
     * @var bool Is plugin active for network
     */
    private static $networkActive;

    /**
     * @var Plugin
     */
    public static $instance;

    /**
     * @return Plugin
     */
    public static function getInstance(): Plugin
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Plugin constructor
     */
    private function __construct()
    {
        $this->registerAutoloader();

        // Load translations
        TextDomain::registerTranslations();

        // Add menu
        Menu::addMenu();

        // Do all configurations for SMTP mailer
        Manager::getInstance()->configureSmtpMails();

        // Rating notices
        \add_action('admin_notices', [$this, 'adminNotices']);
        \add_action('network_admin_notices', [$this, 'adminNotices']);
    }

    /**
     * Register autoloader
     */
    private function registerAutoloader(): void
    {
        require_once KRUT1_SMTP_MAILER_WP_PATH . 'includes/Autoloader.php';

        Autoloader::run();
    }

    /**
     * If plugin network activated
     *
     * @return bool
     */
    public static function isNetworkActive(): bool
    {
        if (self::$networkActive !== null) {
            return self::$networkActive;
        }

        // Makes sure the plugin is defined before trying to use it
        if (!function_exists('is_plugin_active_for_network')) {
            require_once(ABSPATH . '/wp-admin/includes/plugin.php');
        }

        self::$networkActive = \is_multisite() && \is_plugin_active_for_network('smtp-mailer-wp/smtp-mailer-wp.php');

        return self::isNetworkActive();
    }


    /**
     * Show admin notice (rate me)
     */
    public function adminNotices(): void
    {
        // Hide notice for 1/4 year
        if (isset($_GET['smtp_mailer_wp_hide_rate_notice']) && (int)$_GET['smtp_mailer_wp_hide_rate_notice'] === 1) {
            Options::getInstance()->saveOption('next_show_rate_notice_time', time() + 7776000);
        }

        $currentTime = time();
        // Test mail was successful + notice is not disabled + the plugin is working more than 10 days
        if (Options::getInstance()->getOption('successfully_tested') && Options::getInstance()->getOption('next_show_rate_notice_time', 0) < $currentTime && Options::getInstance()->getOption('first_email_sent_time', 0) < $currentTime - 864000) {
            echo '<div class="notice notice-success is-dismissible"><p><img style="float:left;margin:-6px 10px 0 -6px;border-radius:50%" class="theme-author-img" src="/wp-content/plugins/smtp-mailer-wp/img/avatar-author.png" alt="' . __( 'Plugin author', 'smtp-mailer-wp' ) . '" width="32"> <strong style="color:#4d820c">' . __( 'Hey you! I\'m German, the plugin author of SMTP MAILER WP.', 'smtp-mailer-wp' ) . '</strong> ' . __( 'Do you like this plugin? Please show your appreciation and rate the plugin. Help me to develop a powerful plugin that will benefit you for a long time.', 'smtp-mailer-wp' ) . ' <span style="font-size:17px;margin:0 -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span> <span style="font-size:17px;margin:0 -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span> <span style="font-size:17px;margin:0 -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span> <span style="font-size:17px;margin:0 -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span> <span style="font-size:17px;margin:0 -2px;color:rgba(208,174,71,0.57)" class="dashicons dashicons-star-filled"></span> &nbsp;&nbsp;&nbsp;<a href="https://wordpress.org/support/plugin/smtp-mailer-wp/reviews/#new-post" target="_blank">' . __( 'Rate now!', 'smtp-mailer-wp' ) . '</a> &nbsp;&nbsp;&nbsp;<a style="color: lightgrey; text-decoration: none;" href="index.php?smtp_mailer_wp_hide_rate_notice=1">' . __( 'I have already rated.', 'smtp-mailer-wp' ) . '</a></p></div>';
        }
    }
}

Plugin::getInstance();
