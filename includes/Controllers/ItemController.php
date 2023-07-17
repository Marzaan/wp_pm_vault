<?php

namespace PM_Vault\Controllers;
use PM_Vault\Controllers\BaseController;
use PM_Vault\Helpers\Sanitization;

class ItemController extends BaseController{

    public function __construct()
    {
        parent::__construct();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_item_endpoints', [$this, 'ajax_routing']);
        add_action( 'wp_ajax_nopriv_item_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'get_items' => 'show',
            'create_or_update_item' => 'create_or_update',
            'delete_item' => 'destroy',
            'move_items' => 'move_items',
            'delete_items' => 'delete_items'
        ];

        $this->verify_nonce($_REQUEST['nonce']);
        $route = Sanitization::sanitize_value($_REQUEST['route']);

        $this->{$routes[$route]}();
        return;
    }

    public function show( $frontend_request = false ) {
        try {
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
        
            // Decrypt the passwords
            foreach ($items as $item) {
                $decryptedPassword = openssl_decrypt($item->password, 'AES-256-CBC', $this->encryptionKey, 0, $this->encryptionKey);
                $item->password = $decryptedPassword;
            }

            // Request From Frontend
            if($frontend_request){
                return $items;
            }
            
            // Send Response
            $this->sendJsonSuccess($items, 200);

        } catch (\Throwable $th) {
            if($frontend_request){
                return $th->getMessage();
            }
            $this->sendJsonError($th->getMessage(), 500);
        }
    }

    private function create_or_update() {
        try{
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
            
            if(!$name || !$username || !$password){
                $this->sendJsonError("Please fill all the required fields", 400);
                return;
            }
            
            // Remove extra slashes from urls
            $urls = stripslashes($urls);
            
            // Encrypt the password
            $encryptedPassword = openssl_encrypt($password, 'AES-256-CBC', $this->encryptionKey, 0, $this->encryptionKey);
            
            // Data to Update
            $updatedData = [
                'id' => $id,
                'user_id' => $user_id,
                'folder_id' => $folder_id,
                'name' => $name,
                'username' => $username,
                'password' => $encryptedPassword,
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
            wp_send_json($response);

        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }

    private function destroy() {
        try{
            // Get the current user's ID
            $user_id = wp_get_current_user()->ID;
            
            // Requested Item ID
            $item_id = Sanitization::sanitize_value($_POST['id']);
            
            // Check if the item exist and belong to the user
            $item = $this->wpdb->get_row(
                $this->wpdb->prepare(
                    "SELECT id FROM {$this->itemTable} WHERE id = %d AND user_id = %d", $item_id, $user_id
                )
            );
                
            if (!$item) {
                $this->sendJsonError("The item doesn't exist or doesn't belong to the logged-in user.", 404);
                return;
            }
            
            // Delete the folder
            $result = $this->wpdb->delete(
                $this->itemTable,
                ['id' => $item_id]
            );
            
            $this->sendJsonResponse(['id' => $item_id], 'Item Deleted Successfully', 200);

        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }
            
    private function move_items() {
        try{
            // Sanitizing Data
            $ids = rest_sanitize_array($_POST['ids']);
            $folder_id = Sanitization::sanitize_value($_POST['folder_id']);
            
            // Converting ids to a comma-separated string
            $ids_string = implode(',', array_map('absint', $ids));
            
            // SQL update query
            $sql = $this->wpdb->prepare("UPDATE {$this->itemTable} SET folder_id = %s WHERE id IN ($ids_string)", $folder_id);
            
            // Execute the update query
            $result = $this->wpdb->query($sql);
            
            // Send The Response
            if($result){
                $this->sendJsonResponse($result,'Items Moved Successfully', 200);
            }
            else{
                $this->sendJsonError("Items Not Found", 404);
            }

        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }

    private function delete_items() {
        try{
            // Get the current user's ID
            $user_id = wp_get_current_user()->ID;
            
            // Sanitizing ID Array
            $ids = rest_sanitize_array($_POST['ids']);
            
            // Converting ids to a comma-separated string
            $ids_string = implode(',', array_map('absint', $ids));
            
            // SQL delete query
            $sql = "DELETE FROM {$this->itemTable} WHERE id IN ($ids_string) AND user_id = %d";
            
            // Prepare the SQL query with user ID placeholder
            $sql = $this->wpdb->prepare($sql, $user_id);
            
            // Execute the delete query
            $result = $this->wpdb->query($sql);
            
            // Send The Response
            if($result){
                $this->sendJsonResponse($result,'Items Deleted Successfully', 200);
            }
            else{
                $this->sendJsonError("Items Not Found", 404);
            }
        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }
}