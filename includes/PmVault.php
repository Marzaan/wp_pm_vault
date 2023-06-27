<?php

namespace PM_Vault;

use PM_Vault\Enqueue;
use PM_Vault\TextDomain;
use PM_Vault\ShortCode;
use PM_Vault\Controller\FolderController;
use PM_Vault\Controller\ItemController;
use PM_Vault\Controller\ExportController;
use PM_Vault\Controller\ImportController;

class PmVault {

	// The current version of the plugin.
	protected $version;
	protected $plugin_name;
	protected $plugin_dir_path;

	// Construct the plugin object.
	public function __construct() {
		$this->plugin_name = PM_VAULT;
		$this->version = PM_VAULT_VERSION;
		$this->plugin_dir_path = PLUGIN_DIR_PATH;
		
		$this->load_dependencies();
		$this->set_locale();
		$this->register_controllers();
		$this->register_shortcodes();

		add_action('admin_menu', [$this, 'add_menu']);
	}

	private function load_dependencies() {
		require_once($this->plugin_dir_path . 'includes/config/TextDomain.php');
		require_once($this->plugin_dir_path . 'includes/database/DatabaseMigrations.php');
		require_once($this->plugin_dir_path . 'includes/controllers/BaseController.php');
		require_once($this->plugin_dir_path . 'includes/controllers/FolderController.php');
		require_once($this->plugin_dir_path . 'includes/controllers/ItemController.php');
		require_once($this->plugin_dir_path . 'includes/controllers/ExportController.php');
		require_once($this->plugin_dir_path . 'includes/controllers/ImportController.php');
		require_once($this->plugin_dir_path . 'includes/config/Enqueue.php');
		require_once($this->plugin_dir_path . 'includes/config/ShortCode.php');
		require_once($this->plugin_dir_path . 'includes/database/migrations/FolderMigration.php');
		require_once($this->plugin_dir_path . 'includes/database/migrations/ItemMigration.php');
		require_once($this->plugin_dir_path . 'includes/helpers/Sanitization.php');
	}

	private function set_locale() {
		$text_domain = new TextDomain();
		add_action( 'plugins_loaded', [$text_domain, 'load_plugin_textdomain'] );
	}

    public function add_view() {
		$enqueue = new Enqueue();
		$enqueue->admin_enqueue_styles();
		$enqueue->admin_enqueue_scripts();
		$enqueue->admin_localize_scripts();
		$enqueue->enqueue_boostrap();
		$enqueue->enqueue_fontawesome();
        echo "<div id='pm-vault-app'></div>";
	}

    public function add_menu() {
		add_menu_page(
			'PM Vault',
			'PM Vault',
			'manage_options',
			'pm_vault',
			[$this, 'add_view'],
			'dashicons-lock',
			10
		);
    }

	public function register_controllers() {
		(new ItemController())->register_routes();
		(new FolderController())->register_routes();
		(new ExportController())->register_routes();
		(new ImportController())->register_routes();
	}

	public function register_shortcodes(){
		new ShortCode();
	}
}