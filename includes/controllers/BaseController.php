<?php

namespace PM_Vault\Controller;

class BaseController {
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
    }

    protected function verify_nonce($nonce) {
        if (!wp_verify_nonce($nonce, 'pm_vault_nonce')) {
            return wp_send_json_error('Invalid nonce.');
        }
    }
}