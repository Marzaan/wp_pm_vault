<?php

/**
 * @link       https://#
 * @since      1.0.0
 *
 * @package    Pm_Vault
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$table_names = [
	$wpdb->prefix . 'pm_vault_folders',
	$wpdb->prefix . 'pm_vault_items',
];

foreach ($table_names as $table_name) {
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($sql);
}
flush_rewrite_rules();