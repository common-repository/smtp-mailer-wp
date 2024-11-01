<?php
/**
 * Plugin Name: SMTP MAILER WP
 * Plugin URI: https://bitbucket.org/krugerman/wp-plugins
 * Description: Use your personal SMTP mail server (GMAIL, YAHOO etc.) to send emails in your WordPress system.
 * Text Domain: smtp-mailer-wp
 * Domain Path: /languages
 * Version: 1.5
 * Author: German Krutov
 * Author URI: https://profiles.wordpress.org/krut1/
 * License: GPLv3 or later
 * Tested up to: 5.4
 */

define('KRUT1_SMTP_MAILER_WP_VERSION', '1.5');
define('KRUT1_SMTP_MAILER_WP_PATH', plugin_dir_path(__FILE__));
define('KRUT1_SMTP_MAILER_WP_URL', plugins_url('/', __FILE__));
define('KRUT1_SMTP_MAILER_WP_FILE', plugin_basename(__FILE__));
define('KRUT1_SMTP_MAILER_WP_DIR_NAME', basename(__DIR__));

// Load main Class
require KRUT1_SMTP_MAILER_WP_PATH . 'includes/Plugin.php';
