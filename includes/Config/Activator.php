<?php

namespace PM_Vault\Config;

use PM_Vault\Database\DatabaseMigrations;

class Activator {
	public static function activate() {
		DatabaseMigrations::run();
		flush_rewrite_rules();
	}
}
