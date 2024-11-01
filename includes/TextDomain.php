<?php

namespace Krut1SmtpMailerWp;

class TextDomain
{
    public static $domainName = 'smtp-mailer-wp';

    public static function registerTranslations(): void
    {
        \load_plugin_textdomain(self::$domainName, false, KRUT1_SMTP_MAILER_WP_DIR_NAME . '/languages/');
    }
}
