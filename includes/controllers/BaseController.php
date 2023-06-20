<?php

namespace PM_Vault\Controller;

class BaseController {
    protected $wpdb;
    protected $userTable;
    protected $itemTable;
    protected $folderTable;
    protected $encryptionKey;

    public function __construct()
    {
        global $wpdb;

        $this->userTable = $wpdb->prefix . 'users';
        $this->itemTable = $wpdb->prefix . 'pm_vault_items';
        $this->folderTable = $wpdb->prefix . 'pm_vault_folders';
        $this->encryptionKey = 'pm_vault_sec_key';
        $this->wpdb = $wpdb;
    }

    protected function verify_nonce($nonce)
    {
        if (!wp_verify_nonce($nonce, 'pm_vault_nonce')) {
            wp_send_json_error('Invalid nonce.');
        }
    }

    protected function sendJsonResponse($data, $message, $id)
    {
        $response = [
            'data'    => $data,
            'message' => $message  
        ];
        wp_send_json($response, $status);
    }

    protected function sendJsonSuccess($data, $status)
    {
        wp_send_json_success($data, $status);
    }
    
    protected function sendJsonError($message, $status)
    {
        $response = [
            'message' => $message
        ];
        wp_send_json_error($response, $status);
    }
}