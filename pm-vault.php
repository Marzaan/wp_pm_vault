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

if (file_exists(__DIR__.'/vendor/autoload.php')) {
    require __DIR__.'/vendor/autoload.php';
}

use PM_Vault\PmVault;
use PM_Vault\Config\Activator;
use PM_Vault\Config\Deactivator;

// If this file is called directly, abort.
if ( ! defined( 'ABSPATH' ) ) {
	die;
}

/**
 * The plugin name and version
*/
defined( 'PM_VAULT' ) or define( 'PM_VAULT', true );
defined( 'PM_VAULT_VERSION' ) or define( 'PM_VAULT_VERSION', '1.0.0_' . microtime() );
defined( 'PLUGIN_DIR_PATH' ) or define( 'PLUGIN_DIR_PATH', plugin_dir_path(__FILE__));

/**
 * The code that runs during plugin activation.
*/
function activate_pm_vault() {
	Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
*/
function deactivate_pm_vault() {
	Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_pm_vault' );
register_deactivation_hook( __FILE__, 'deactivate_pm_vault' );

// Redirect User on Activation
function redirect_on_activation( $plugin )
{
	if ( $plugin == plugin_basename( __FILE__)) {
		wp_redirect(admin_url('admin.php?page=pm_vault'));
		die();
	}
}
add_action('activated_plugin', 'redirect_on_activation');

// Footer Text Modification
function footer_text_modify( $text ) {
	return 'PM Vault © 2023';
}
add_filter('admin_footer_text', 'footer_text_modify');

/**
 * Begins execution of the plugin.
*/

function run_pm_vault() {
	new PmVault();
}
run_pm_vault();


?>