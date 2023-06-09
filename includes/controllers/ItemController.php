<?php

namespace PM_Vault\Controller;

class ItemController {
    protected $wpdb;
    protected $itemTable;
    protected $userTable;

    public function __construct()
    {
        global $wpdb;

        $this->itemTable = $wpdb->prefix . 'pm_vault_items';
        $this->userTable = $wpdb->prefix . 'users';
        $this->wpdb = $wpdb;
        $this->register_routes();
    }

    public function register_routes()
    {
        add_action( 'wp_ajax_get_items', [$this, 'show']);
        add_action( 'wp_ajax_create_item', [$this, 'store']);
    }

    public function show()
    {
        $user = wp_get_current_user();
        $items = $this->wpdb->get_results(
            "SELECT *
            FROM {$this->itemTable} AS items
            JOIN {$this->userTable} AS users ON users.ID = items.user_id"
        );
        $response = [
            'success' => true,
            'data' => $items
        ];
        // Send the JSON-encoded response
        return wp_send_json($items);
    }

    public function store()
    {
        $user = wp_get_current_user();
        $folder_id = $_POST['folder_id'];
        $name = $_POST['name'];
        $type = $_POST['type'];
        $notes = $_POST['notes'];
        $favorite = $_POST['favorite'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        $result = $wpdb->insert($this->itemTable, [
            'user_id'    => $user->ID,
            'folder_id'  => $folder_id,
            'name'       => $name,
            'type'       => $type,
            'notes'      => $notes,
            'favorite'   => $favorite,
            'username'   => $username,
            'password'   => $password,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($result) {
            $response = [
                'success' => true,
                'data' => $result
            ];
        } else {
            $response = [
                'success' => false,
                'data' => $result
            ];
        }

        return wp_send_json($response);
    }
}