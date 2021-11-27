<?php
/**
 * @author Flames
 *
 * @link Flames#0698
 *
 * @license GNU General Public License Version 3
 */
// Отключаем вывод ошибок.
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

// Ограничиваем время выполнения скрипта.
set_time_limit(4);

// Нахожение в пространстве LR.
define('IN_LR', true);

// Основная директория вэб-приложения.
define('APP', '../../../../app/');

// Основная директория вэб-приложения.
define('STORAGE', '../../../../storage/');

// Директория содержащая основные блоки вэб-приложения.
define('PAGE', APP . 'page/general/');

// Директория содержащая дополнительные блоки вэб-приложения.
define('PAGE_CUSTOM', APP . 'page/custom/');

// Директория с модулями.
define('MODULES', APP . 'modules/');

// Директория с основными конфигурационными файлами.
define('INCLUDES', APP . 'includes/');

// Директория содержащая графические кэш-файлы.
define('CACHE', STORAGE . 'cache/');

// Директория с ресурсами.
define('ASSETS', STORAGE . 'assets/');

// Директория с основными кэш-файлами.
define('SESSIONS', CACHE . 'sessions/');

// Директория содержащая логи.
define('LOGS', CACHE . 'logs/');

// Директория содержащая изображения.
define('IMG', CACHE . 'img/');

// Директория с CSS шаблонами.
define('ASSETS_CSS', ASSETS . 'css/');

// Директория с JS библиотеками.
define('ASSETS_JS', ASSETS . 'js/');

// Директория с шаблонами "Themes".
define('THEMES', ASSETS_CSS . 'themes/');

// Директория с изображениями рангов.
define('RANKS_PACK', IMG . 'ranks/');

// Временные константы ( Постоянные времени ) - Минута.
define('MINUTE_IN_SECONDS', 60);

// Временные константы ( Постоянные времени ) - Час.
define('HOUR_IN_SECONDS', 3600);

// Временные константы ( Постоянные времени ) - День.
define('DAY_IN_SECONDS', 86400);

// Временные константы ( Постоянные времени ) - Неделя.
define('WEEK_IN_SECONDS', 604800);

// Временные константы ( Постоянные времени ) - Месяц.
define('MONTH_IN_SECONDS', 2592000);

// Временные константы ( Постоянные времени ) - Год.
define('YEAR_IN_SECONDS', 31536000);

session_start();
// Регистраниция основных функций.
require '../../../includes/functions.php';

// Импортирование класса базы данных.
require_once '../../../ext/Db.php';

// Импортирование класса отвечающего за работу с языками и переводами.
require_once '../../../ext/Translate.php';

// Импортирование класса отвечающего за работу с уведомлениями.
require_once '../../../ext/Notifications.php';

// Импортирование основного класса настроек.
require_once '../../../ext/General.php';

// Роутер..
require_once '../../../ext/AltoRouter.php';

// Импортирование класса отвечающего за работу с модулями.
require_once '../../../ext/Modules.php';

// Импортирование класса отвечающего за работу с авторизацией.
require_once '../../../ext/Auth.php';

require_once MODULES . 'module_page_sb/ext/Rcon.php';

require_once MODULES . 'module_page_sb/ext/qb/PdoxInterface.php';

require_once MODULES . 'module_page_sb/ext/qb/Cache.php';

require_once MODULES . 'module_page_sb/ext/qb/Pdox.php';

require_once MODULES . 'module_page_sb/ext/sb.php';

$Translate      = new \app\ext\Translate;

$Db             = new \app\ext\Db();

$Notifications  = new \app\ext\Notifications ( $Translate, $Db );

$General        = new \app\ext\General ( $Db );

$Router         = new \app\ext\AltoRouter();

empty( $General->arr_general['site'] ) && $General->arr_general['site'] = '//' . preg_replace('/^(https?:)?(\/\/)?(www\.)?/', '', $_SERVER['HTTP_REFERER']);

$Modules        = new \app\ext\Modules       ( $General, $Translate, $Notifications, $Router );

$Auth           = new \app\ext\Auth          ( $General, $Db );

$sb = new \app\modules\module_page_sb\ext\sb( $Translate, $General, $Modules );

if(isset($_POST['function'])) {
    switch ($_POST['function']) {
        case 'update_ban':
            echo json_encode($sb->UpdateBan());
        break;
        case 'install':
            echo json_encode($sb->Install());
        break;
        case 'add_server':
            echo json_encode($sb->AddServer());
        break;
        case 'del_server':
            echo json_encode($sb->DelServer());
        break;
        case 'del_admin':
            echo json_encode($sb->DelAdmin());
        break;
        case 'add_admin':
            echo json_encode($sb->AddAdmin());
        break;
        case 'edit_server':
            echo json_encode($sb->EditServer());
        break;
        case 'edit_admin':
            echo json_encode($sb->EditAdmin());
        break;
        case 'add_ban':
            echo json_encode($sb->AddBan());
        break;
        case 'getbans':
            echo json_encode($sb->SearchBans());
        break;
        case 'del_ban':
            echo json_encode($sb->DelBan());
        break;
        case 'add_group':
            echo json_encode($sb->AddGroup());
        break;
        case 'del_group':
            echo json_encode($sb->DeleteGroup());
        break;
        case 'edit_group':
            echo json_encode($sb->EditGroup());
        break;
    }
}