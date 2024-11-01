<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Page
 *
 * @package Krut1SmtpMailerWp
 */
class Page
{
    /**
     * Show setting page
     */
    public static function settingsPage(): void
    {
        include KRUT1_SMTP_MAILER_WP_PATH . 'templates/settings-page.php';
    }
}
