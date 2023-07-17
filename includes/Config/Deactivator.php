<?php

namespace PM_Vault\Config;

class Deactivator {

	public static function deactivate() {
		flush_rewrite_rules();
	}

}
