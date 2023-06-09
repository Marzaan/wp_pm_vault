<?php

/**
 * The plugin bootstrap file
 * @link              https://#
 * @since             1.0.0
 * @package           Pm_Vault
 *
 * @wordpress-plugin
 * Plugin Name:       PM Vault
 * Plugin URI:        https://#
 * Description:       This is a description of the plugin.
 * Version:           1.0.0
 * Author:            Raiyan Marzan
 * Author URI:        https://#
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pm-vault
 * Domain Path:       /languages
 */

use PM_Vault\PmVault;
use PM_Vault\Activator;
use PM_Vault\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The plugin name.
*/
defined( 'Plugin_Name' ) or define( 'Plugin_Name', 'PM Vault' );
/**
 * Currently plugin version.
*/
defined( 'PM_VAULT_VERSION' ) or define( 'PM_VAULT_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
*/
function activate_pm_vault() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/config/Activator.php';
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
*/
function deactivate_pm_vault() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/config/Deactivator.php';
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pm_vault' );
register_deactivation_hook( __FILE__, 'deactivate_pm_vault' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
*/
require plugin_dir_path( __FILE__ ) . 'includes/PmVault.php';

/**
 * Begins execution of the plugin.
*/
function run_pm_vault() {
	new PmVault();
}
run_pm_vault();

// Redirect User on Activation
function redirectOnActivation( $plugin )
{
	if ( $plugin == plugin_basename( __FILE__)) {
		wp_redirect(admin_url('admin.php?page=pm_vault'));
		die();
	}
}
add_action('activated_plugin', 'redirectOnActivation');

?>