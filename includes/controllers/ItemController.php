<?php

namespace PM_Vault\Controller;
use PM_Vault\helper\Sanitization;

class ItemController {
    protected $wpdb;
    protected $userTable;
    protected $itemTable;
    protected $folderTable;

    public function __construct()
    {
        global $wpdb;

        $this->userTable = $wpdb->prefix . 'users';
        $this->itemTable = $wpdb->prefix . 'pm_vault_items';
        $this->folderTable = $wpdb->prefix . 'pm_vault_folders';
        $this->wpdb = $wpdb;
        $this->register_routes();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_item_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'get_items' => 'show',
            'create_or_update_item' => 'create_or_update',
            'delete_item' => 'destroy'
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
            "SELECT items.*, users.ID, folders.foldername
            FROM {$this->itemTable} AS items
            LEFT JOIN {$this->userTable} AS users ON users.ID = items.user_id
            LEFT JOIN {$this->folderTable} AS folders ON folders.id = items.folder_id
            WHERE items.user_id = %d",
            $user_id
        );

        // Get the items
        $items = $this->wpdb->get_results($prepared_query);
        $response = [
            'success' => true,
            'data' => $items
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
        $folder_id = Sanitization::sanitize_value($_POST['folderID']);
        $username = Sanitization::sanitize_value($_POST['username']);
        $password = Sanitization::sanitize_value($_POST['password']);
        $urls = Sanitization::sanitize_value(json_encode($_POST['urls']));
        $notes = Sanitization::sanitize_value($_POST['notes']);
        $favorite = Sanitization::sanitize_with_fallback_zero($_POST['favorite']);
        $urlss = Sanitization::sanitize_value($_POST['urls']);

        // Remove extra slashes from urls
        $urls = stripslashes($urls);

        // Data to Update
        $updatedData = [
            'id' => $id,
            'user_id' => $user_id,
            'folder_id' => $folder_id,
            'name' => $name,
            'username' => $username,
            'password' => $password,
            'urls' => $urls,
            'notes' => $notes,
            'favorite' => $favorite,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Add New or Update Existing Item
        $result = $this->wpdb->replace(
            $this->itemTable, $updatedData
        );

        // Check if the item was created or updated
        $response = [
            'success' => $result,
            'data' => [
                'id' => $result ? (($id) ? $id : $this->wpdb->insert_id) : '',
                'values' => $id ? $updatedData : '',
                'updated' => $id ? true : false
            ],
            'message' => $result ? (($id) ? 'Item Updated Successfully' : 'Item Created Successfully') : 'Item Update Failed'
        ];
        return wp_send_json($response);
    }

    public function destroy() {
        // Verify the nonce
        $nonce = $_POST['nonce'];
        if (!wp_verify_nonce($nonce, 'pm_vault_nonce')) {
            return wp_send_json_error('Invalid nonce.');
        }

        // Delete the item
        $id = Sanitization::sanitize_value($_POST['id']);
        $result = $this->wpdb->delete(
            $this->itemTable,
            ['id' => $id]
        );

        // Prepare the response
        $response = [
            'success' => $result,
            'data' => [
                'id' => $result ? $id : ''
            ],
            'message' => $result ? 'Item Deleted Successfully' : 'Item Delete Failed'
        ];
        return wp_send_json($response);
    }
}