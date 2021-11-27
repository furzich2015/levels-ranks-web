<?php
$configs = require MODULES . 'module_page_sb/temp/config.php';
if(isset( $_SESSION['steamid32'] ) && isset( $_SESSION['user_admin'] )) { 
    $Modules->set_sidebar_select('module_page_sb', ["href" =>"?page=sb", "open_new_tab" =>"0", "icon_group" =>"zmdi", "icon_category" =>"", "icon" =>"settings", "name" =>"Панель SB", "sidebar_directory" =>""]);
} elseif(!isset($_SESSION['user_admin']) && $configs['settings']['enable_nonadmin'] == '1' && !empty($Db->db_data['SourceBans'])) {
    $Modules->set_sidebar_select('module_page_sb', ["href" =>"?page=sb", "open_new_tab" =>"0", "icon_group" =>"zmdi", "icon_category" =>"", "icon" =>"block", "name" =>"Блокировки", "sidebar_directory" =>""]);
}