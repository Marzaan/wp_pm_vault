<?php

namespace PM_Vault\Controller;
use PM_Vault\Controller\BaseController;
use PM_Vault\helper\Sanitization;

class ExportController extends BaseController {

    public function __construct()
    {
        parent::__construct();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_export_endpoints', [$this, 'ajax_routing']);
    }

    public function ajax_routing(){
        $routes = [
            'export_items' => 'export_items'
        ];

        $this->verify_nonce($_REQUEST['nonce']);
        $route = Sanitization::sanitize_value($_REQUEST['route']);

        $this->{$routes[$route]}();
        return;
    }

    private function export_items() {
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
        $items = $this->wpdb->get_results($prepared_query, ARRAY_A);

        // CSV File Column Headers
        $columnHeaders = [
            'No', 'Folder Name', 'Name', 'Username', 'Password', 'URLs', 'Notes', 'Favorite', 'Updated At'
        ];

        // Initialize the CSV data
        $indx = 1;
        $csvData = [];
        $csvData[] = $columnHeaders;

        // Process the results and format the data
        foreach ($items as $row) {
            $json_urls = json_decode($row['urls']);
            $urls = implode(' | ', $json_urls);
            $formattedRow = [
                $indx++,
                $row['foldername'],
                $row['name'],
                $row['username'],
                $row['password'],
                $urls,
                $row['notes'],
                $row['favorite'] ? 'Yes' : 'No',
                date('Y-m-d H:i:s', strtotime($row['updated_at'])),
            ];
            $csvData[] = $formattedRow;
        }

        // Generate the CSV content
        $csvContent = '';
        foreach ($csvData as $row) {
            $csvContent .= implode(',', $row) . "\n";
        }

        $response = [
            'success' => true,
            'data' => $csvContent
        ];
        return wp_send_json($response);
    }
}