<?php

namespace PM_Vault\Controller;
use PM_Vault\helper\Sanitization;

class FolderController {

    protected $wpdb;
    protected $folderTable;
    protected $userTable;

    public function __construct() {
        global $wpdb;

        $this->folderTable = $wpdb->prefix . 'pm_vault_folders';
        $this->userTable = $wpdb->prefix . 'users';
        $this->wpdb = $wpdb;
        $this->register_routes();
    }

    public function register_routes() {
        add_action( 'wp_ajax_folder_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'get_folders' => 'show',
            'create_or_update_folder' => 'create_or_update',
            'delete_folder' => 'destroy'
        ];

        $route = Sanitization::sanitize_value($_REQUEST['route']);

        $this->{$routes[$route]}();
        return;
    }

    public function show() {
        // Get the current user's ID
        $user_id = wp_get_current_user()->ID;

        // Prepare the query
        $prepared_query = $this->wpdb->prepare(
            "SELECT folders.*, users.ID 
            FROM {$this->folderTable} AS folders
            INNER JOIN {$this->userTable} AS users ON users.ID = folders.user_id
            WHERE folders.user_id = %d",
            $user_id
        );

        // Get the folders
        $folders = $this->wpdb->get_results($prepared_query);
        $response = [
            'success' => true,
            'data' => $folders
        ];
        return wp_send_json($response);
    }

    public function create_or_update() {
        // Verify the nonce
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'pm_vault_nonce')) {
            return wp_send_json_error('Invalid nonce.');
        }

        // Get the current user's ID
        $user_id = wp_get_current_user()->ID;

        // Sanitize the data
        $id = Sanitization::sanitize_value($_POST['id']);
        $name = Sanitization::sanitize_value($_POST['name']);

        // Add New or Update Existing Folder
        $result = $this->wpdb->replace(
            $this->folderTable, 
            [
                'id' => $id,
                'user_id' => $user_id,
                'foldername' => $name,
                'updated_at' => date('Y-m-d H:i:s')
            ]
        );
        
        // Check if the folder was created or updated
        $response = [
            'success' => $result,
            'data' => [
                'id' => $result ? (($id) ? $id : $this->wpdb->insert_id) : '',
                'name' => $name,
                'updated' => $id ? true : false
            ],
            'message' => $result ? (($id) ? 'Folder Updated Successfully' : 'Folder Created Successfully') : 'Folder Update Failed'
        ];
        return wp_send_json($response);
    }

    public function destroy() {
        // Verify the nonce
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'pm_vault_nonce')) {
            return wp_send_json_error('Invalid nonce.');
        }

        // Delete the folder
        $id = Sanitization::sanitize_value($_POST['id']);
        $result = $this->wpdb->delete(
            $this->folderTable,
            ['id' => $id]
        );

        // Prepare the response
        $response = [
            'success' => $result,
            'data' => [
                'id' => $result ? $id : ''
            ],
            'message' => $result ? 'Folder Deleted Successfully' : 'Folder Delete Failed'
        ];
        return wp_send_json($response);
    }
}