<?php 
if(!empty($Db->db_data['SourceBans'])) {

    //Проверка на страницы админа
    if(empty($_GET['bid']) && empty($_GET['cid']) && empty($_GET['c']) && isset($_SESSION['user_admin']) || !file_exists( MODULES . 'module_page_sb/includes/'.$_GET['c'].'.php' ) && isset($_SESSION['user_admin']) && empty($_GET['bid']) && empty($_GET['cid'])) 
        require_once MODULES . 'module_page_sb/includes/general.php';

    //Проверка на существование таких страниц
    if(!empty($_GET['c']) && file_exists( MODULES . 'module_page_sb/includes/'.$_GET['c'].'.php' ) && isset($_SESSION['user_admin']))
        require_once MODULES . 'module_page_sb/includes/'. $_GET['c'] . '.php';

    //Если включены нонстим показ банов
    if(!isset($_SESSION['user_admin']) && $config == '1')
        require_once MODULES . 'module_page_sb/includes/nonbans.php';

    //Наконец изменение
    if(!isset($_GET['c']) && isset($_GET['bid']) || isset($_GET['cid']))
        require_once MODULES . 'module_page_sb/includes/editban.php';

//Если все плохо
} elseif(isset($_SESSION['user_admin'])) {
    require_once MODULES . 'module_page_sb/includes/install.php';
}