<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Menu
 *
 * @package Krut1SmtpMailerWp
 */
class Menu
{
    /**
     * Add menu elements for plugin
     */
    public static function addMenu(): void
    {
        $menuPrefix = Plugin::isNetworkActive() ? 'network_' : '';
        $actionsLinksPrefix = Plugin::isNetworkActive() ? 'network_admin_' : '';

        // Add network menu page
        \add_action($menuPrefix . 'admin_menu', [__CLASS__, 'createMenu']);

        // Add settings link on plugin page
        \add_filter($actionsLinksPrefix . 'plugin_action_links_' . KRUT1_SMTP_MAILER_WP_FILE, [__CLASS__, 'addSettingsPage']);
    }

    /**
     * Add menu to network admin panel
     */
    public static function createMenu(): void
    {
        $capability = Plugin::isNetworkActive() ? 'manage_network' : 'manage_options';

        \add_menu_page(
            __('SMTP Mailer', 'smtp-mailer-wp'),
            __('SMTP Mailer', 'smtp-mailer-wp'),
            $capability,
            'krut1_smtp_mailer_settings',
            [Page::class, 'settingsPage'],
            'dashicons-email'
        );
    }

    /**
     * @param $links
     * @return mixed
     */
    public static function addSettingsPage($links)
    {
        $settings_link = '<a href="admin.php?page=krut1_smtp_mailer_settings">' . __('Settings', 'smtp-mailer-wp') . '</a>';
        array_unshift($links, $settings_link);

        return $links;
    }
}
