<?php

namespace PM_Vault\Controller;

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
        add_action( 'wp_ajax_get_folders', [$this, 'show']);
        add_action( 'wp_ajax_create_folder', [$this, 'store']);
    }

    public function show() {
        $user = wp_get_current_user();
        $folders = $this->wpdb->get_results(
            "SELECT *
            FROM {$this->folderTable} AS folders
            JOIN {$this->userTable} AS users ON users.ID = folders.user_id"
        );
        $response = [
            'success' => true,
            'data' => $folders
        ];
        return wp_send_json($response);
    }

    public function store() {
        $user = wp_get_current_user();
        $name = $_POST['foldername'];

        $result = $wpdb->insert($this->folderTable, [
            'user_id'    => $user->ID,
            'foldername' => $name,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        if($result) {
            $response = [
                'success' => true,
                'message' => 'Folder created successfully'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Folder creation failed'
            ];
        }
        return wp_send_json($response);
    }
}