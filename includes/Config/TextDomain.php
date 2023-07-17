<?php

namespace PM_Vault\Config;

class TextDomain {

	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'pm-vault',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
