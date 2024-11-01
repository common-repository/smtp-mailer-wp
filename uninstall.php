<?php

if( ! defined('WP_UNINSTALL_PLUGIN') ) exit;

// Clear DB
delete_option('krut1_smtp_mailer_options');