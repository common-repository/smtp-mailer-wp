<?php

\defined('ABSPATH') || die('Keep Silent');

use Krut1SmtpMailerWp\Options;

// Handle request with test email
if (isset($_POST['krut1_smtp_mailer_test'])) {
    add_action('wp_mail_failed', 'krut1_smtp_mailer_show_test_mail_error', 10, 1);

    $email = sanitize_email($_POST['krut1_smtp_mailer_test']['email']);
    $subject = sanitize_text_field($_POST['krut1_smtp_mailer_test']['subject']);
    $msg = sanitize_textarea_field($_POST['krut1_smtp_mailer_test']['msg']);

    // Validate data and send test message
    if (empty(Options::getInstance()->getOption('host'))) {
        echo '<div class="notice notice-error is-dismissible"><p>' . sprintf(__('Please configure at first your <a href=\'%s\'>smtp settings</a>.', 'smtp-mailer-wp'), 'admin.php?page=krut1_smtp_mailer_settings&tab=smtp-settings') . '</p></div>';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo '<div class="notice notice-error is-dismissible"><p>' . __('From email address is not valid.', 'smtp-mailer-wp') . '</p></div>';
    } elseif (wp_mail($email, $subject, $msg)) {
        Options::getInstance()->saveOption('successfully_tested', true);
        Options::getInstance()->saveOption('first_email_sent_time', time());
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Email was sent.', 'smtp-mailer-wp') . '</p></div>';
    } else {
        Options::getInstance()->saveOption('successfully_tested', false);
        echo '<div class="notice notice-warning is-dismissible"><p>' . __('Email was not sent.', 'smtp-mailer-wp') . '</p></div>';
    }
}
?>
    <p><?= __('Here you can test your SMTP settings. Enter your email address and check if you receive a message.', 'smtp-mailer-wp') ?></p>
    <p><?= __('If the message is not sent, you will see an error message.', 'smtp-mailer-wp') ?></p>
    <form action="" method="POST">
        <table class="form-table">
            <tr>
                <td><label for="krut1_smtp_mailer_test_address"><?= __('Email', 'smtp-mailer-wp') ?></label></td>
                <td><input type="text" id="krut1_smtp_mailer_test_address" name="krut1_smtp_mailer_test[email]"/></td>
            </tr>
            <tr>
                <td><label for="krut1_smtp_mailer_test_subject"><?= __('Subject', 'smtp-mailer-wp') ?></label></td>
                <td><input type="text" id="krut1_smtp_mailer_test_subject" name="krut1_smtp_mailer_test[subject]" value="<?= __('Test Subject!', 'smtp-mailer-wp') ?>"/></td>
            </tr>
            <tr>
                <td><label for="krut1_smtp_mailer_test_msg"><?= __('Message', 'smtp-mailer-wp') ?></label></td>
                <td><textarea id="krut1_smtp_mailer_test_msg" name="krut1_smtp_mailer_test[msg]"><?= __('Hi, I am a test email!', 'smtp-mailer-wp') ?></textarea></td>
            </tr>
            <tr>
                <td colspan="2"><?php submit_button(); ?></td>
            </tr>
        </table>
    </form>
<?php

/**
 * @param WP_Error $wpError
 */
function krut1_smtp_mailer_show_test_mail_error($wpError)
{
    echo '<div class="notice notice-error is-dismissible"><p>' . $wpError->get_error_message() . '</p></div>';
}
