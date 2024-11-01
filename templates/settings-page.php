<?php

\defined('ABSPATH') || die('Keep Silent');

$tabs = [
    'smtp-settings' => __('SMTP Settings', 'smtp-mailer-wp'),
    'test-send-mail' => __('Test mail', 'smtp-mailer-wp')
];

// Default current tab
$currentTab = 'smtp-settings';

// Validate get params with tab name
if (isset($_GET['tab'], $tabs[$_GET['tab']])) {
    $currentTab = $_GET['tab'];
}

?>
<div class="wrap smtp-mailer-wp">
    <h2><?= get_admin_page_title() ?></h2>
    <h2 class="nav-tab-wrapper">
        <?php foreach ($tabs as $tab => $tabName) : ?>
            <a class="nav-tab<?= $currentTab === $tab ? ' nav-tab-active' : '' ?>" href="?page=krut1_smtp_mailer_settings&tab=<?= $tab ?>"><?= $tabName ?></a>
        <?php endforeach; ?>
    </h2>

    <?php
    // Include tabs template
    include KRUT1_SMTP_MAILER_WP_PATH . "templates/settings-tabs/{$currentTab}.php";
    ?>
</div>