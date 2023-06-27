<?php

namespace PM_Vault;

use PM_Vault\Enqueue;
use PM_Vault\Controller\FolderController;
use PM_Vault\Controller\ItemController;

class ShortCode {

    public function __construct()
    {
        add_shortcode('pm_vault_form', [$this, 'item_form_shortcode']);
        add_shortcode('pm_vault_items', [$this, 'item_list_shortcode']);
    }

    public function enqueue_custom_assets()
    {
        $enqueue = new Enqueue();
        $enqueue->public_enqueue_styles();
        $enqueue->public_enqueue_scripts();
        $enqueue->public_localize_scripts();
        $enqueue->enqueue_boostrap();
    }

    public function item_form_shortcode( $atts = [], $content = null )
    {
        ob_start();
        $this->enqueue_custom_assets();
        $foldersData = [];

        $folders = new FolderController();
        $frontend_request = true;
        $foldersData = $folders->show($frontend_request);

        include(PLUGIN_DIR_PATH . 'src/public/views/ItemForm.php');
        return ob_get_clean();
    }

    public function item_list_shortcode( $atts = [], $content = null )
    {
        ob_start();
        $this->enqueue_custom_assets();
        $itemsData = [];

        $items = new ItemController();
        $frontend_request = true;
        $itemsData = $items->show($frontend_request);
    
        include(PLUGIN_DIR_PATH . 'src/public/views/ItemList.php');
        return ob_get_clean();
    }
}
