<?php

\defined('ABSPATH') || die('Keep Silent');

use Krut1SmtpMailerWp\Options;

$secureSelect = [
    'tls' => __('Ssl', 'smtp-mailer-wp'),
    'ssl' => __('Tls', 'smtp-mailer-wp')
];

// Get all options
$options = Options::getInstance()->getOptions();

// Handle request with options
if (isset($_POST['krut1_smtp_mailer_options'])) {

    $error = null;

    // Trim all strings in received data
    $options = [
        'user' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['user']),
        'pass' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['pass']),
        'host' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['host']),
        'from' => \sanitize_email($_POST['krut1_smtp_mailer_options']['from']),
        'name' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['name']),
        'port' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['port']),
        'secure' => \sanitize_text_field($_POST['krut1_smtp_mailer_options']['secure'])
    ];

    // Trim and remove empties values
    $options = array_filter(array_map('stripslashes', array_map('trim', $options)));

    /////////////////////
    // Validate values //
    /////////////////////

    // Validate email
    if (isset($options['from']) && !filter_var($options['from'], FILTER_VALIDATE_EMAIL)) {
        $error = __('From email address is not valid.', 'smtp-mailer-wp');
    }

    // Validate hostname (if reachable)
    if (!filter_var(gethostbyname($options['host']), FILTER_VALIDATE_IP)) {
        $error = __('Hostname is not valid.', 'smtp-mailer-wp');
    }

    // Validate port number
    if ((int)$options['port'] <= 0 || !filter_var($options['port'], FILTER_VALIDATE_INT)) {
        $error = __('Port number is not valid.', 'smtp-mailer-wp');
    }

    // Validate connection type
    if (!isset($secureSelect[$options['secure']])) {
        $error = __('Encryption system is not valid.', 'smtp-mailer-wp');
    }

    // Save or show error
    if ($error === null) {
        Options::getInstance()->saveOptions($options);
        echo '<div class="notice notice-success is-dismissible"><p>' . __('Options were saved.', 'smtp-mailer-wp') . '</p></div>';
    } else {
        echo '<div class="notice notice-error is-dismissible"><p>' . $error . '</p></div>';
    }
}
?>
<form action="" method="POST">
    <table class="form-table">
        <tr>
            <td><label for="krut1_smtp_mailer_user"><?= __('User name for SMTP authentication', 'smtp-mailer-wp') ?></label></td>
            <td><input type="text" id="krut1_smtp_mailer_user" name="krut1_smtp_mailer_options[user]" value="<?= htmlspecialchars($options['user'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td><label for="krut1_smtp_mailer_pass"><?= __('Password for SMTP authentication', 'smtp-mailer-wp') ?></label></td>
            <td><input type="password" id="krut1_smtp_mailer_pass" name="krut1_smtp_mailer_options[pass]" value="<?= htmlspecialchars($options['pass'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td><label for="krut1_smtp_mailer_host"><?= __('Hostname', 'smtp-mailer-wp') ?></label></td>
            <td><input type="text" id="krut1_smtp_mailer_host" name="krut1_smtp_mailer_options[host]" value="<?= htmlspecialchars($options['host'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td>
                <label for="krut1_smtp_mailer_from"><?= __('From email addresse', 'smtp-mailer-wp') ?></label>
                <br><small><?= __('* Leave the field blank to use the default sender email', 'smtp-mailer-wp') ?></small>
            </td>
            <td><input type="text" id="krut1_smtp_mailer_from" name="krut1_smtp_mailer_options[from]" value="<?= htmlspecialchars($options['from'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td>
                <label for="krut1_smtp_mailer_name"><?= __('From name', 'smtp-mailer-wp') ?></label>
                <br><small><?= __('* Leave the field blank to use the default sender name', 'smtp-mailer-wp') ?></small>
            </td>
            <td><input type="text" id="krut1_smtp_mailer_name" name="krut1_smtp_mailer_options[name]" value="<?= htmlspecialchars($options['name'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td><label for="krut1_smtp_mailer_port"><?= __('Port number (25, 465 or 587)', 'smtp-mailer-wp') ?></label></td>
            <td><input type="text" id="krut1_smtp_mailer_port" name="krut1_smtp_mailer_options[port]" value="<?= htmlspecialchars($options['port'] ?? '') ?>"/></td>
        </tr>
        <tr>
            <td><label for="krut1_smtp_mailer_secure"><?= __('Encryption system', 'smtp-mailer-wp') ?></label></td>
            <td>
                <select id="krut1_smtp_mailer_secure" name="krut1_smtp_mailer_options[secure]">
                    <?php foreach ($secureSelect as $type => $name): ?>
                        <option value="<?= $type ?>"<?= ($options['secure'] ?? null) === $type ? ' selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <td colspan="2"><?php submit_button(); ?></td>
        </tr>
    </table>
</form>
<p><?= __('* If all the entered data is correct, but emails are still not sent, then try changing the port number or encryption system.', 'smtp-mailer-wp') ?></p>
