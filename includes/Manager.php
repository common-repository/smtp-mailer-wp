<?php

namespace Krut1SmtpMailerWp;

/**
 * Class Manager
 *
 * @package Krut1SmtpMailerWp
 */
class Manager
{
    /**
     * @var Manager
     */
    private static $instance;

    /**
     * @return Manager
     */
    public static function getInstance(): Manager
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
    }

    /**
     * Configure php mailer
     */
    public function configureSmtpMails(): void
    {
        // Rewrite default sender
        \add_action('phpmailer_init', [$this, 'configureSmtpMail'], 999);

        // Deactivate regexp validation. See also https://stackoverflow.com/questions/52336131/why-wont-i-send-an-email-thru-wordpress-when-i-can-send-it-thru-a-php-script
        \add_filter('wp_mail_from', [$this, 'deactivateRegexpValidation'], 1);

        // Replace from address
        \add_filter('wp_mail_from', [$this, 'replaceFromIfExists'], 999);

        // Replace from name
        \add_filter('wp_mail_from_name', [$this, 'replaceFromNameIfExists'], 999);

        // Write log if error
        \add_action('wp_mail_failed', [$this, 'writeErrorLog'], 10, 1);
    }

    /**
     * @param \PHPMailer $phpmailer
     * @return \PHPMailer
     */
    public function configureSmtpMail($phpmailer): \PHPMailer
    {
        if (Options::getInstance()->getOption('host') !== null) {
            $phpmailer->isSMTP();
            $phpmailer->SMTPAuth = true;
            $phpmailer->Host = Options::getInstance()->getOption('host');
            $phpmailer->Port = Options::getInstance()->getOption('port');
            $phpmailer->Username = Options::getInstance()->getOption('user');
            $phpmailer->Password = Options::getInstance()->getOption('pass');
            $phpmailer->SMTPSecure = Options::getInstance()->getOption('secure');
            $phpmailer->AuthType = 'PLAIN';
            // Remove X-Mailer PHPMailer headers
            $phpmailer->XMailer = ' ';

            // We set it, because our application should work, if the mail server not reachable...
            $phpmailer->Timeout = 10;
        }

        return $phpmailer;
    }

    /**
     * @param $from
     * @return mixed
     */
    public function deactivateRegexpValidation($from)
    {
        \PHPMailer::$validator = 'noregex';

        return $from;
    }

    /**
     * @param $from
     * @return mixed
     */
    public function replaceFromIfExists($from)
    {
        // Replace email from
        if (!empty(Options::getInstance()->getOption('from'))) {
            $from = Options::getInstance()->getOption('from');
        }

        return $from;
    }

    /**
     * @param $fromName
     * @return mixed
     */
    public function replaceFromNameIfExists($fromName)
    {
        // Replace email from
        if (!empty(Options::getInstance()->getOption('name'))) {
            $fromName = Options::getInstance()->getOption('name');
        }

        return $fromName;
    }

    /**
     * @param \WP_Error $wpError
     */
    public function writeErrorLog($wpError): void
    {
        // We don't need in Logs the body of message: too big
        $errorData = $wpError->get_error_data();
        if (is_array($errorData) && isset($errorData['message'])) {
            unset($errorData['message']);
        }

        error_log('[KRUT1-SMTP-MAILER-WP] Error: #' . $wpError->get_error_code() . ', ' . $wpError->get_error_message() . "\nData:\n" . print_r($errorData, true));
    }
}
