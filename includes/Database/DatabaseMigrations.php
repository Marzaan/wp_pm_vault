<?php

namespace PM_Vault\Database;

use PM_Vault\Database\Migrations\FolderMigration;
use PM_Vault\Database\Migrations\ItemMigration;

class DatabaseMigrations {

	public static function run() {
		FolderMigration::migrate();
		ItemMigration::migrate();
	}

}
