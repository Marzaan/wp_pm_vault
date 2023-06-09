<?php

namespace PM_Vault\Database\Migrations;

class ItemMigration {

	public static function migrate() {
        global $wpdb;

        $table_name = $wpdb->prefix . 'pm_vault_items';
    
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
            user_id int NOT NULL,
            folder_id int NOT NULL,
            name varchar(255) NOT NULL,
            notes text,
            favorite boolean default 0,
            username varchar(255),
            password varchar(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            urls JSON,
			PRIMARY KEY  (id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
    }

}