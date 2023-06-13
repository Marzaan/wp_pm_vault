<?php

namespace PM_Vault;

class Enqueue {

	private $plugin_name;
	private $version;

	public function __construct() {
		$this->plugin_name = PM_VAULT;
		$this->version = PM_VAULT_VERSION;
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			 'custom_css', 
			 plugins_url('../../dist/css/pm-vault-public.css', __FILE__ ), 
			 array(),
			 $this->version,
			 'all'
		 );
		 wp_enqueue_style(
			'font-awesome',
			'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
			array(),
			'6.4.0',
			'all'
		);
		// Enqueue Bootstrap CSS
		wp_enqueue_style(
			'bootstrap',
			'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css',
			array(),
			'5.0.2',
			'all'
		);
	}

	public function enqueue_scripts() {
        wp_enqueue_script(
			'custom_js', 
			plugins_url('../../dist/js/pm-vault-public.js', __FILE__ ), 
			array(),
			$this->version,
			true
		);

		// Enqueue Bootstrap JavaScript
		wp_enqueue_script(
			'bootstrap-bundle',
			'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js',
			array('jquery'),
			'5.0.2',
			true
		);	
	}

	public function localize_scripts(){
		wp_localize_script( 'custom_js', 'ajax_object', array( 
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'pm_vault_nonce' )
		) );
	}
}
