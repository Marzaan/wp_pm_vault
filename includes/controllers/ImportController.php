<?php

namespace PM_Vault\Controller;
use PM_Vault\Controller\BaseController;
use PM_Vault\helper\Sanitization;

class ImportController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_import_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'import_items' => 'import_items'
        ];

        $this->verify_nonce($_REQUEST['nonce']);
        $route = Sanitization::sanitize_value($_REQUEST['route']);

        $this->{$routes[$route]}();
        return;
    }

    private function get_folder_id($foldername, $user_id) {
        if($foldername == null || $foldername == '') {
            return null;
        }
        
        // Prepare the query
        $prepared_query = $this->wpdb->prepare(
            "SELECT folders.id
            FROM {$this->folderTable} AS folders
            WHERE folders.foldername = %s AND folders.user_id = %d",
            $foldername,
            $user_id
        );

        // Get the folder ID
        $folder_id = $this->wpdb->get_var($prepared_query);

        // If the folder doesn't exist, create it
        if (!$folder_id) {
            $result = $this->wpdb->insert($this->folderTable, [
                'user_id'    => $user_id,
                'foldername' => $foldername,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $folder_id = $this->wpdb->insert_id;
        }

        return $folder_id;
    }

    private function import_items() {
        // Get the current user's ID
        $user_id = wp_get_current_user()->ID;

        $result = true;
        $message = '';
        $file = $_FILES['file']['tmp_name'];
        $file_name = $_FILES['file']['name'];
        $file_size = $_FILES['file']['size'];
        $allowed_formats = ['csv'];
        $max_file_size = 2 * 1024 * 1024; // 2 MB
        $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));

        if(empty($file) || !is_uploaded_file($file)) {
            $message = 'No file uploaded';
            $result = false;
        }

        // Validate the file format and size
        if ($result && (!in_array($file_extension, $allowed_formats) || $file_size > $max_file_size)) {
            $message = 'Invalid file format or size';
            $result = false;
        }

        // If the file is valid, import the items
        if (($result && ($handle = fopen($file, 'r')) !== false)) {
            // Header Row Skipping
            fgets($handle);

            while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                $foldername = Sanitization::sanitize_value($row[1]);
                $name = Sanitization::sanitize_value($row[2]);
                $username = Sanitization::sanitize_value($row[3]);
                $password = Sanitization::sanitize_value($row[4]);
                $urlsString = Sanitization::sanitize_value($row[5]);
                $notes = Sanitization::sanitize_value($row[6]);
                $favorite = Sanitization::sanitize_value($row[7]);
                
                // Get the folder ID
                $folder_id = $this->get_folder_id($foldername, $user_id);
                
                // Formatting the URLs
                $urlsArray = explode(' | ', $urlsString);
                $jsonUrls = json_encode($urlsArray);
    
                // Prepare the insert query
                $result = $this->wpdb->insert($this->itemTable, [
                    'user_id'    => $user_id,
                    'folder_id'  => $folder_id,
                    'name'       => $name,
                    'username'   => $username,
                    'password'   => $password,
                    'urls'       => $jsonUrls,
                    'notes'      => $notes,
                    'favorite'   => $favorite,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
            fclose($handle);
        } else {
            $message = $result ? 'Error opening the file' : $message;
            $result = false;
        }

        $response = [
            'success' => $result,
            'message' => $result ? 'Items imported successfully': $message,
        ];
        return wp_send_json($response);
    }
}