<?php

namespace PM_Vault;

class Deactivator {

	public static function deactivate() {
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
	}

}
