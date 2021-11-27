<?php
/**
 * @author Flames
 *
 * @link Flames#0698
 *
 * @license GNU General Public License Version 3
 */
defined('IN_LR') != true && die();

//Вызов класса
use app\modules\module_page_sb\ext\sb;

use app\modules\module_page_sb\ext\qb\Pdox;

//Присваивание класса к переменной
$sb      =   new sb( $Translate, $General, $Modules );

$bd      =   new Pdox();

if(!empty($Db->db_data['SourceBans']))
{
    $config  =  require_once MODULES . 'module_page_sb/temp/config.php';

    if(isset($_POST['startpagination'])) 
    { 
        exit(json_encode($sb->LoadBans($_POST['startpagination'], $_POST['server'], $_POST['type'])));
    }

    $bans    =  $sb->LoadBans(1, 'all');

    $servers =  $sb->GetServers();

    //Сами понимаете, рофл, лол кек такой
    if(!isset($_SESSION['user_admin']) && $config['settings']['enable_nonadmin'] == '0')
    {
        get_iframe('009', 'Данная страница не существует');
    }

    isset($_POST['steamidload']) && exit($sb->LoadProfile($_POST['steamidload']));

    if(isset($_SESSION['user_admin'])):

        $page_num = (int) intval ( get_section( 'num', '1' ) );

        $admins  =  $sb->GetAdmins($page_num);

        $serversp = $sb->GetPagServers($page_num);

        if(!empty($_GET['c'])):
            switch ($_GET['c']) {
                case 'admins':
                    $page_max = ceil($sb->CountAdmins() / 10);
                break;

                case 'servers':
                    $page_max = ceil($sb->CountServers() / 10);
                break;

                case 'groups':
                    $page_max = ceil($sb->CountGroups() / 10);
                break;
            }

            if(!empty($page_max)) {
                $page_num > $page_max && get_iframe( '009', 'Данная страница не существует' );

                $page_num <= 0 && get_iframe( '009', 'Данная страница не существует' );
            }
        endif;

        $groups  =  $sb->GetGroups();

        if(isset($_GET['aid'])):

            $info = $sb->AdminInfo($_GET['aid']);

            empty($info) && get_iframe('009', 'Админа такого нет в нашей вселенной :[');

        endif;

        if(isset($_GET['bid'])) {
            $binfo = $sb->BansInfo($_GET['bid'], 1);

            if(empty($binfo)) get_iframe('404', 'Бан не найден');

            $server = $sb->CheckServerID($binfo->sid);

            $typeban = 'ban';

        } elseif(isset($_GET['cid'])) {
            $binfo = $sb->BansInfo($_GET['cid'], 0);

            if(empty($binfo)) get_iframe('404', 'Мут не найден');

            $server = $sb->CheckServerID($binfo->sid);

            $typeban = 'mute';
        }
    endif;
    
} elseif(!isset($_SESSION['user_admin'])) {
    get_iframe('009', 'Данная страница не существует');
}