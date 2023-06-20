<?php

namespace PM_Vault\Controller;
use PM_Vault\Controller\BaseController;
use PM_Vault\helper\Sanitization;

class FolderController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_folder_endpoints', [$this, 'ajax_routing']);
        add_action( 'wp_ajax_nopriv_folder_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'get_folders' => 'show',
            'create_or_update_folder' => 'create_or_update',
            'delete_folder' => 'destroy'
        ];

        $this->verify_nonce($_REQUEST['nonce']);
        $route = Sanitization::sanitize_value($_REQUEST['route']);

        $this->{$routes[$route]}();
        return;
    }


    public function show( $frontend_request = false ) {
        try{
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
            
            // Request From Frontend
            if($frontend_request){
                return $folders;
            }
            
            // Send Response
            $this->sendJsonSuccess($folders, 200);
        
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

            if(!$name){
                $this->sendJsonError("Folder Name is Required", 400);
                return;
            }

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
            wp_send_json($response);

        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }

    private function destroy() {
        try{
            // Get the current user's ID
            $user_id = wp_get_current_user()->ID;

            // Requested Folder ID
            $folder_id = Sanitization::sanitize_value($_POST['id']);

            // Check if the folder exist and belong to the user
            $folder = $this->wpdb->get_row(
                $this->wpdb->prepare(
                    "SELECT id FROM {$this->folderTable} WHERE id = %d AND user_id = %d", $folder_id, $user_id
                )
            );

            if (!$folder) {
                $this->sendJsonError("The folder doesn't exist or doesn't belong to the logged-in user.", 404);
                return;
            }

            // Delete the folder
            $result = $this->wpdb->delete(
                $this->folderTable,
                ['id' => $folder_id]
            );

            $this->sendJsonResponse(['id' => $folder_id], 'Folder Deleted Successfully', 200);
        
        } catch (\Throwable $th) {
            $this->sendJsonError($th->getMessage(), 500);
        }
    }
}