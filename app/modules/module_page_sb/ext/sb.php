<?php
/**
 * @author Flames
 *
 * @link Flames#0698
 *
 * @license GNU General Public License Version 3
 */

namespace app\modules\module_page_sb\ext;

use app\modules\module_page_sb\ext\Rcon;

use app\modules\module_page_sb\ext\qb\Pdox;

use PDO;

use PDOException;

class sb extends Pdox {

    /**
     * @since 0.2.122
     * @var object
     */
    public $Translate;

    /**
     * @since 0.2.122
     * @var object
     */
    public $General;

    /**
     * @since 0.2.122
     * @var object
     */
    public $Modules;

    /**
     * Импортирование классов, короче конструктор
     *
     * @param object $General
     * @param object $Translate
     * @param object $Modules
     * @param object $Db
     *
     * @since 0.2
     */
    public function __construct( $Translate, $General, $Modules ) 
    {
        parent::__construct();
        
        //Проверка на константу
        defined('IN_LR') != true && die();

        //Если не админ, то до связи
        if(!isset($_SESSION['user_admin']) && $this->GetSettings()['settings']['enable_nonadmin'] == '0') die();

        //Импортирование переводов
        $this->Translate = $Translate;

        //Импортирование настроек сайта
        $this->General = $General;

        //Импортирование класса модулей
        $this->Modules = $Modules;
    }

    /**
     * Установка модуля.
     *
     * @since 0.2
     *
     * @return array            Выводит массив со всеми серверами
     */
    public function Install() {

        if(isset($_SESSION['user_admin'])):

            //Импортирование всех баз
            $db = require SESSIONS . '/db.php';

            if(empty($_POST['host']) || empty($_POST['db_1']) || empty($_POST['table']) || empty($_POST['user']) || empty($_POST['mod']) || empty($_POST['name']))
                return ['error' => 'Введены не все значения!'];

            $_RCON = $this->RconConnect($_POST['ip'], $_POST['port']);

            if(!$_RCON)
                return ['error' => 'Подключение к серверу отсутствует!'];

            $_RCON->Disconnect();

            try {

                if(empty($db['SourceBans'])) {

                    //Проверка на подключение к БД
                    $con = new PDO("mysql:host=".$_POST['host'].";dbname=".$_POST['db_1'], $_POST['user'], $_POST['pass']);

                    //Запрос
                    $sth = $con->query("SELECT * FROM ".rtrim($_POST['table'], '_') . '_'."admins LIMIT 1");

                    //Проверка на наличие таблиц
                    if(!$sth) {

                        //SQL файлик
                        $sql = file_get_contents(MODULES . 'module_page_sb/temp/data.sql');
                        
                        //Замена {prefix} на твой префикс
                        $con->exec(str_replace("{prefix}", rtrim($_POST['table'], '_') . '_', $sql));

                        //Импортирование CONSOLE
                        $con->exec("INSERT INTO ". rtrim($_POST['table'], '_') . '_' . "admins (`aid` ,	`user` , `authid` ,	`password` , `gid` , `email` ,	`validate` , `extraflags`, `immunity`)VALUES (NULL , 'CONSOLE', 'STEAM_ID_SERVER', '', '0', '', NULL, '0', 0);");

                        //Импортирование сервера
                        $con->exec("INSERT INTO ". rtrim($_POST['table'], '_') . '_' . "servers (`sid`, `ip`, `port`, `rcon`, `modid`) VALUES (NULL, '{$_POST['ip']}' , {$_POST['port']}, '{$_POST['rcon']}', 22)");

                        //Удаление файлов установки, не знаю зачем, просто чтобы не было
                        unlink(MODULES . 'module_page_sb/temp/data.sql');

                        unlink(MODULES . 'module_page_sb/includes/install.php');

                        //Удаление идиотских картинок
                        for($i = 1; $i <= 3; $i++) {
                            unlink(MODULES . 'module_page_sb/temp/img/install'.$i.'.jpg');
                        }
                    }
                    
                    //Запрос на добавление в $db
                    $query = ['HOST' => $_POST['host'],'PORT' => $_POST['ports'], 'USER' => $_POST['user'], 'PASS' => $_POST['pass'], 'DB' =>  [0 => ['DB' => $_POST['db_1'],'Prefix' =>  [0 => [ 'table' => rtrim($_POST['table'], '_') . '_', 'name' => $_POST['name'],'mod' => $_POST['mod'],'steam' => $_POST['nosteam'],],],],],];
                    
                    //Ну тут очевидно
                    $db['SourceBans'][] = $query;

                    //Запись этого добра в файл
                    file_put_contents( SESSIONS . 'db.php', '<?php return '.var_export_opt( $db, true ).";" );

                    //Success.
                    return ['success' => $this->Translate->get_translate_module_phrase( 'module_page_adminpanel','_success_db_created')];
                    
                    header("Refresh:2");

                } else {
                    return ['error' => $this->Translate->get_translate_module_phrase( 'module_page_adminpanel','_error')];
                }

                //Обнуляем PDO
                $con = null;
            } catch (PDOException $e) {
                return ['error' => $this->Translate->get_translate_module_phrase( 'module_page_adminpanel','_error_con_db')]; 
            }
        endif;
    }

    /**
     * Получение серверов.
     *
     * @since 0.2
     *
     * @return array            Выводит массив со всеми серверами
     */
    public function GetServers() 
    {
        return $this->table('servers')->getall();
    }

    /**
     * Получение серверов с пагинацией.
     *
     * @since 0.2
     *
     * @return array            Выводит массив со всеми серверами
     */
    public function GetPagServers($num) {
        return $this->table('servers')->pagination(10, intval(trim($num)))->getall();
    }

    /**
     * Проверка на наличие такого флага.
     *
     * @since 0.2
     *
     * @return boolean            Ну логично
     */
    public function PregFlags($flag, $flags) {
        if(preg_match("/".$flag."/i", $flags)) return true;
        else return false;
    }

     /**
     * Получение админов.
     *
     * @since 0.2
     *
     * @return array            Выводит массив со всеми админами, которые не CONSOLE
     */
    public function GetAdmins($num) 
    {
        return $this->table('admins')->pagination(10, intval(trim($num)))->where('user', '!=', 'CONSOLE')->getall();
    }

    /**
     * Получение количества серверов.
     *
     * @since 0.2
     *
     * @return int            Количество серверов
     */
    public function CountServers() {
        return intval(sizeof($this->table('servers')->getall()));
    }

    /**
     * Получение количества групп.
     *
     * @since 0.2
     *
     * @return int            Количество серверов
     */
    public function CountGroups() {
        return intval(sizeof($this->table('srvgroups')->getall()));
    }

    /**
     * Проверка на существование сервера
     *
     * @since 0.2
     *
     * @param int $ip           IP сервера.
     * @param int $port         PORT сервера.
     *
     * @return boolean          Возвращает кэш модуля.
     */
    public function GetServer($ip, $port) 
    {
        $check = $this->table('servers')->where('ip', $ip)->where('port', $port)->getall();
        if(!empty($check)) 
        {
            return false;
        } 
        else 
        {
            return true;
        }
    }

    /**
     * Добавление сервера в БД
     *
     * @since 0.2
     *
     * @return array          Возвращает результат.
     */
    public function AddServer() 
    {
        !isset($_SESSION['user_admin']) && die();

        if(empty($_POST['ip']) || empty($_POST['port']) || empty($_POST['rcon'])) return ['error' => 'Введены не все значения!'];

        $_POST['enabled'] = $_POST['enabled'] == true ? 1 : 0;
        $params = [
            'priority' => 0,
            'ip' => $_POST['ip'],
            'port' => $_POST['port'],
            'rcon' => $_POST['rcon'],
            'modid' => 22,
            'enabled' => $_POST['enabled']
        ];
        $_RCON = $this->RconConnect($_POST['ip'], $_POST['port']);
        if($_RCON) {
            if($this->GetServer($_POST['ip'], $_POST['port'])) 
            {
                $this->table('servers')->insert($params);
                return ['success' => 'Сервер добавлен!', 'header' => '/sb/?c=servers'];
            } 
            else 
            {
                return ['error' => 'Сервер уже существует'];
            }
            $_RCON->Disconnect();
        } else {
            return ['error' => 'Не удается подключится к серверу'];
        }
    }

    /**
     * Удаление сервера из бд
     *
     * @since 0.2
     *
     * @return array          Возвращает результат.
     */
    public function DelServer() 
    {
        !isset($_SESSION['user_admin']) && die();

        //Сука, ну нормально вводите, заебали
        if(empty($_POST['param1'])) return ['error' => 'Блять.'];

        $this->table('servers')->where('sid', $_POST['param1'])->delete();

        return ['success' => 'Удален ваш сервер'];
    }

     /**
     * Получение статистики админа, по его ID
     *
     * @since 0.2
     * 
     * @param int $id         ID админа из БД.
     *
     * @return array          Возвращает результат.
     */
    public function GetStats($id) 
    {
        //Зачем все в переменные сувать? Так надо!
        $bans = $this->table('bans')->where('aid', $id)->getall();
        $comms = $this->table('comms')->where('aid', $id)->getall();
        $info = $this->table('admins')->where('aid', $id)->getall();
        return ['bans' => sizeof($bans), 'comms' => sizeof($comms), 'info' => $info];
    }

    /**
     * Получение нормальной даты
     *
     * @since 0.2
     * 
     * @param int $time    UNIX время
     *
     * @return string      Возвращает результат.
     */
    public function GetNormalDate($time) 
    {
        //Логично? Логично.
        if($time == '0' || $time == NULL || empty($time)) return 'Никогда';
        else return date("d.m.Y", $time);
    }

    /**
     * Удаление админа
     *
     * @since 0.2
     * 
     * @return array          Возвращает результат.
     */
    public function DelAdmin() 
    {
        !isset($_SESSION['user_admin']) && die();
        $this->table('admins')->where('aid', $_POST['param1'])->delete();
        return ['success' => 'Админ удален!'];
    }

    /**
     * Получение информации об админе
     *
     * @since 0.2
     * 
     * @param int $aid      ID админа из БД.
     *
     * @return array        Возвращает результат.
     */
    public function AdminInfo($aid) 
    {
        !isset($_SESSION['user_admin']) && die();
        return $this->table('admins')->where('aid', $aid)->where('authid', '!=', 'STEAM_ID_SERVER')->get();
    }

     /**
     * Инфо о бане
     *
     * @since 0.2
     * 
     * @param int $bid      ID бана из БД.
     * @param int $type     Тип, мут - 0, бан - 1
     *
     * @return array        Возвращает результат.
     */
    public function BansInfo($bid, $type) 
    {
        !isset($_SESSION['user_admin']) && die();
        switch ($type) {
            case 1:
                return $this->table('bans')->where('bid', intval($bid))->get();
            break;
            case 0:
                return $this->table('comms')->where('bid', intval($bid))->get();
            break;
        }
    }

    /**
     * Получение админа из бд, по логину или STEAMID
     *
     * @since 0.2
     * 
     * @param string $steamid       STEAMID админа.
     * @param string $login         Логин админа.
     *
     * @return boolean              Возвращает результат.
     */
    private function CheckAdmin($steamid, $login) 
    {
        //"Ты так любишь substr? У тебя же есть Get32($steamid, 1)!" - Да мне похрен!
        $ad = $this->table('admins')->like('authid', '%'. substr($steamid, 10) .'%')->orwhere('user', '=', $login)->get();
        if(!empty($ad)) return true;
        else return false;
    }

    /**
     * Подключение к серверу, по его IP:PORT
     *
     * @since 0.2
     * 
     * @param int $ip           IP сервера.
     * @param int $port         PORT сервера.
     *
     * @return boolean          Возвращает результат.
     */
    public function RconConnect($ip, $port) 
    {
        //"Где try нахуй?" - А он что возвращает? Разве не bool?
        $rcon = new Rcon($ip, $port);
        if($rcon->Connect()) return $rcon;
    }

    /**
     * Получение информации о группе
     *
     * @since 0.2
     * 
     * @param int $id           ID группы.
     *
     * @return array            Возвращает результат.
     */
    public function GetGroup($id) 
    {
        return $this->table('srvgroups')->where('id', $id)->get();
    }

    /**
     * Добавление администратора.
     *
     * @since 0.2
     * 
     * @return array            Возвращает результат.
     */
    public function AddAdmin() 
    {
        !isset($_SESSION['user_admin']) && die();
        
        //Проверка на пустоту сервера
        if(empty($_POST['servers'])) return ['error' => 'Выберете сервер!'];

        //Проверка на STEAMID, почему не использовать Get32? Я ебу?
        if(preg_match('/^STEAM_[0-9]{1,2}:[0-1]:\d+$/', $_POST['steam']) == false) return ['error' => 'Введи STEAM_X:Y:Z!'];

        //Нету логина
        if(empty($_POST['login'])) return ['error' => 'Введены не все значения!'];

        //Ну это ясно
        if($this->CheckAdmin($_POST['steam'], $_POST['login']) == true) return ['error' => 'Такой админ уже существует!'];

        //Просто на апелляцию нужно, вместо on поменять на 1
        $_POST['enabled_support'] = $_POST['enabled_support'] == true ? 1 : 0;

        //Максимальный ID админа
        $adminid = $this->table('admins')->max('aid')->get();

        for($i = 0; $i < sizeof($_POST['servers']); $i++) 
        {
            $zalupa = $this->table('servers')->where('sid', $_POST['servers'][$i])->get();
            if(!empty($zalupa->sid) && $zalupa->sid == $_POST['servers'][$i]) 
            {
                if($zalupa->enabled == '1') 
                {
                    $command = $this->RconConnect($zalupa->ip, $zalupa->port);
                    if($command) 
                    {
                        $command->RconPass($zalupa->rcon);
                        $command->Command('sm_reloadadmins');
                        $command->Disconnect();
                    }
                }
                $data = [
                    'admin_id' =>       $adminid->{"MAX(aid)"} + 1,
                    'group_id' =>       '0',
                    'srv_group_id' =>   '0',
                    'server_id' =>      $_POST['servers'][$i]
                ];
                $this->table('admins_servers_groups')->insert($data);
            }
        }
        if($_POST['selectgroup'] == 'select') 
        {
            $flags      =   implode($_POST['flags']);
            $imm        =   $_POST['immune'];
            $groupid    =   NULL;
        }
        else 
        {
            $group      =   $this->GetGroup($_POST['selectgroup']);
            $flags      =   $group->flags;
            $imm        =   intval($group->immunity);
            $groupid    =   $group->name;
        }
        if($_POST['date'] != '0') 
        {
            $date2      =   $_POST['date'] * 60;
            $length     =   time() + $date2;
        } 
        else 
        {
            $length     =   '0';
        }
        $adata = [
            'aid' =>            $adminid->{"MAX(aid)"} + 1,
            'user' =>           $_POST['login'],
            'authid' =>         $_POST['steam'],
            'password' =>       '',
            'gid' =>            '0',
            'email' =>          '',
            'extraflags' =>     '0',
            'immunity' =>       $imm,
            'srv_group' =>      $groupid,
            'srv_flags' =>      $flags,
            'expired' =>        $length,
            'skype' =>          $_POST['skype'] ?? NULL,
            'comment' =>        $_POST['comment'] ?? NULL,
            'vk' =>             $_POST['vk'] ?? NULL,
            'support' =>        $_POST['enabled_support']
        ];
        $this->table('admins')->insert($adata);
        return ['success' => 'Админ добавлен!'];
    }

    /**
     * Просмотр флага.
     *
     * @since 0.2
     * 
     * @param string $flag      Флаг.
     * 
     * @param int $aid          Номер админа.
     * 
     * @return boolean          Возвращает результат.
     */
    public function CheckFlag($flag, $aid) 
    {
        $admin = $this->table('admins')->where('aid', $aid)->get();
        if(!empty($admin->srv_group)) 
        {
            $flags = $this->table('srvgroups')->where('name', $admin->srv_group)->get();
            if(preg_match("/".$flag."/i", $flags->flags)) return true;
        } 
        else 
        {
            if(preg_match("/".$flag."/i", $admin->srv_flags)) return true;
        }
    }

    /**
     * Получение групп.
     *
     * @since 0.2
     * 
     * @return array            Возвращает результат.
     */
    public function GetGroups() 
    {
        !isset($_SESSION['user_admin']) && die();
        return $this->table('srvgroups')->getall();
    }

    /**
     * Изменение сервера.
     *
     * @since 0.2
     * 
     * @return array            Возвращает результат.
     */
    public function EditServer() 
    {
        !isset($_SESSION['user_admin']) && die();
        $check = $this->table('servers')->where('sid', $_POST['param1'])->get();
        if(!empty($check)) 
        {
            if(empty($_POST['ip']) || empty($_POST['port']) || empty($_POST['rcon'])) return ['error' => 'Введены не все значения!'];
            $_POST['enabled'] = $_POST['enabled'] == true ? 1 : 0;
            if($check->ip == $_POST['ip'] && $check->port == $_POST['port'] && $check->rcon == $_POST['rcon'] && $check->enabled == $_POST['enabled']) return ['error' => 'А че поменялось?'];
            $data = [
                'ip' => $_POST['ip'],
                'port' => $_POST['port'],
                'rcon' => $_POST['rcon'],
                'enabled' => $_POST['enabled']
            ];
            $this->table('servers')->where('sid', $_POST['param1'])->update($data);
            return ['success' => 'Сервер изменен!'];
        } 
        else 
        {
            return ['error' => 'Сервер не найден!'];
        }
    }

    /**
     * Проверка на наличие сервера.
     *
     * @since 0.2
     * 
     * @param int $aid          Номер админа.
     * 
     * @param int $sid          Номер сервера.
     * 
     * @return array            Возвращает результат.
     */
    public function CheckServer($aid, $sid) 
    {
        $check = $this->table('admins_servers_groups')->where('admin_id', $aid)->where('server_id', $sid)->get();
        if(!empty($check)) return 'checked=""';
    }

    /**
     * Изменение админа.
     *
     * @since 0.2
     * 
     * @return array            Возвращает результат.
     */
    public function EditAdmin() 
    {
        !isset($_SESSION['user_admin']) && die();
        $admin = $this->AdminInfo($_POST['param1']);
        if(empty($_POST['servers'])) return ['error' => 'Выберете сервер'];
        if(!empty($admin)) 
        {
            $this->table('admins_servers_groups')->where('admin_id', $_POST['param1'])->delete();

            //Я гений, придумал проще
            for($i = 0; $i < sizeof($_POST['servers']); $i++) 
            {
                $zalupa = $this->table('servers')->where('sid', $_POST['servers'][$i])->get();
                if(!empty($zalupa->sid) && $zalupa->sid == $_POST['servers'][$i]) 
                {
                    if($zalupa->enabled == '1') 
                    {
                        $command = $this->RconConnect($zalupa->ip, $zalupa->port);
                        if($command) 
                        {
                            $command->RconPass($zalupa->rcon);
                            $command->Command('sm_reloadadmins');
                            $command->Disconnect();
                        }
                    }
                    $data = [
                        'admin_id' =>       $_POST['param1'],
                        'group_id' =>       '0',
                        'srv_group_id' =>   '0',
                        'server_id' =>      $_POST['servers'][$i]
                    ];
                    $this->table('admins_servers_groups')->insert($data);
                }
            }
            if(empty($data)) return ['error' => 'Произошла ошибка стыковки'];
            //А есть альтернатива?
            if($_POST['selectgroup'] == 'select') 
            {
                $flags = implode($_POST['flags']);
                $imm = $_POST['immune'];
                $groupid = NULL;
            } 
            else 
            {
                $group = $this->GetGroup($_POST['selectgroup']);
                $flags = $group->flags;
                $imm   = intval($group->immunity);
                $groupid = $group->name;
            }
            if(!empty($_POST['date']) && $_POST['date'] != '0') 
            {
                $date2 = date_create(date("H:i:s", strtotime("now")));
                date_modify($date2, '+'.$_POST['date'].' day');
                $date2 = strtotime($date2->format("Y-m-d H:i:s"));
            } 
            elseif($_POST['date'] == '0')
            {
                $date2 = '0';
            } 
            else 
            {
                $date2 = $admin->expired;
            }
            $_POST['enabled_support'] = $_POST['enabled_support'] ? 1 : 0;
            $date = [
                'user' =>       $_POST['login'],
                'authid' =>     $_POST['steam'],
                'immunity' =>   $imm,
                'expired' =>    $date2,
                'skype' =>      $_POST['skype'] ?? NULL,
                'vk' =>         $_POST['vk'] ?? NULL,
                'comment' =>    $_POST['comment'] ?? NULL,
                'srv_flags' =>  $flags,
                'srv_group' =>  $groupid,
                'support' =>    $_POST['enabled_support']
            ];
            $this->table('admins')->where('aid', $_POST['param1'])->update($date);
            return ['success' => 'Админ изменен!'];
        } 
        else 
        {
            return ['error' => 'Админа не существует'];
        }
    }

    /**
     * Конвертирование в STEAMID
     *
     * @since 0.2
     * 
     * @param string $steamid   STEAM человека.
     * 
     * @return string           Возвращает результат.
     */
    protected function Get32($steamid, $int) {
        //Зачем substr если есть int, хороший вопрос
        if(preg_match('/^STEAM_[0-9]{1,2}:[0-1]:\d+$/', $steamid)) $steam = $steamid;
        if(preg_match( '/^(7656119)([0-9]{10})$/', $steamid)) $steam = con_steam64to32($steamid);
        if(preg_match('/^\[U:(.*)\:(.*)\]$/', $steamid)) $steam = con_steam3to32_int(substr($steamid, 4, -1));
        if(!empty($steam)) {
            if($int == 1)
                return substr($steam, 10);
            else
                return $steam;
        }
    }

    /**
     * Добавление бана
     *
     * @since 0.2
     * 
     * @return array           Возвращает результат.
     */
    public function AddBan() 
    {
        !isset($_SESSION['user_admin']) && die();

        //Конвертирование в STEAMID
        $steam = $this->Get32($_POST['steam'], 0);

        //Логично? Логично.
        if(empty($steam)) return ['error' => 'Не понял, ты что ввел?'];

        //Это уже хакермены страны
        if(empty($_POST['sid']) || empty($_POST['reason']) || empty($_POST['time'])) return ['error' => 'Введены не все значения!'];

        //Алгебра 7 класс
        if($_POST['time'] == '0') $date = '0'; else $date = strtotime("now") + $_POST['time'] * 86400;

        //Тож ясно
        $admin = $this->CheckAdmin($_SESSION['steamid32'], '');

        //Реально жопа
        if(empty($admin)) return ['error' => 'Добавь себя в админы, иначе жопа'];
        switch ($_POST['type']) 
        {
            case 'ban':
                $data = [
                    'name' => $_POST['nickname'],
                    'authid' => $steam,
                    'created' => strtotime("now"),
                    'ends' => $date,
                    'length' => $_POST['time'] * 86400 ?? '0',
                    'reason' => $_POST['reason'],
                    'aid' => $admin,
                    'adminIp' => $_SESSION['steamid32'],
                    'sid' => $_POST['sid'],
                    'country' => $_SESSION['language'],
                    'type' => 0
                ];
                $this->table('bans')->insert($data);
                return ['success' => 'Бан добавлен!'];
            break;
            //Country где сука
            case 'mute': 
                $data = [
                    'name' => $_POST['nickname'],
                    'authid' => $steam,
                    'created' => strtotime("now"),
                    'ends' => $date,
                    'length' => $_POST['time'] * 86400 ?? '0',
                    'reason' => $_POST['reason'],
                    'aid' => $admin,
                    'adminIp' => $_SESSION['steamid32'],
                    'sid' => $_POST['sid'],
                    'type' => 1
                ];
                $this->table('comms')->insert($data);
                return ['success' => 'Мут добавлен!'];
            break;
            case 'gag': 
                $data = [
                    'name' => $_POST['nickname'],
                    'authid' => $steam,
                    'created' => strtotime("now"),
                    'ends' => $date,
                    'length' => $_POST['time'] * 86400 ?? '0',
                    'reason' => $_POST['reason'],
                    'aid' => $admin,
                    'adminIp' => $_SESSION['steamid32'],
                    'sid' => $_POST['sid'],
                    'type' => 2
                ];
                $this->table('comms')->insert($data);
                return ['success' => 'Мут добавлен!'];
            break;
            default:
                return ['error' => 'Чего бля?'];
            break;
        }
    }

    /**
     * Получение информации о бане
     *
     * @since 0.2
     * 
     * @param object $type      Типо массив с информацией о бане
     * 
     * @return string           Возвращает результат.
     */
    public function GetLength($type) 
    {
        $ban_type = [
            0 => '<div class="color-red">' . $this->Translate->get_translate_phrase('_Forever') . '</div>',
            1 => '<div class="color-blue">' . $this->Translate->get_translate_phrase('_Unban') . '</div>',
            2 => '<strike>Сессия</strike>'
        ];
        if ($type->length == '0' && $type->RemoveType != 'U') 
        {
            $length = $ban_type['0'];
        } 
        elseif ($type->RemoveType == 'U') 
        {
            $length = $ban_type['1'];
        } 
        elseif ($type->length < '0' && time() >= $type->ends) 
        {
            $length = $ban_type['2'];
        } 
        elseif (time() >= $type->ends && $type->length != '0') 
        {
            $length = '<div class="color-green"><strike>' . $this->Modules->action_time_exchange( $type->length ) . '</strike></div>';
        }  
        else
        {
            $length = $this->Modules->action_time_exchange( $type->length );
        }
        return $length;
    }

    /**
     * Получение информации о какой блокировке идет речь
     *
     * @since 0.2
     * 
     * @param string $type      Ну ясно что это
     * 
     * @return string           Возвращает результат.
     */
    public function GetType($type, $ban) {
        if($ban == 'ban') {
            switch ($type) 
            {
                case 0:
                    return 'Бан по стиму';
                break;
                case 1:
                    return 'Бан по айпи';
                break;
                case -1:
                    return 'Бан по стиму и айпи';
                break;
            }
        } elseif($ban == 'mute') {
            switch ($type) 
            {
                case 2:
                    return 'Гаг';
                break;
                case 1:
                    return 'Мут';
                break;
                case 3:
                    return 'Мут + гаг';
                break;
            }
        }
    }

    /**
     * Загрузка блокировок
     *
     * @since 0.2
     * 
     * @param int $num         Номер страницы
     * 
     * @param int $server      Номер сервера
     * 
     * @return array           Возвращает результат.
     */
    public function LoadBans($num, $server, $type = 'all') 
    {
        //Че за говнозапрос, пиздец говнокод
        if($server != 'all') 
        {
            if($type == 'all') {
                $info = array_merge($this->select(['*','type AS c_type'])
                        ->table('comms')
                        ->pagination('50', $num)
                        ->where('sid', (int) $server)
                        ->orderby('created desc')
                        ->getall()
                        ,
                        $this->select(['*','type AS b_type'])
                        ->table('bans')
                        ->pagination('50', $num)
                        ->where('sid', (int) $server)
                        ->orderby('created desc')
                        ->getall());
            } else {
                $where = [
                    'sid' => (int) $server
                ];
                switch($type) {
                    case 'mute':
                        $info = $this->select(['*','type AS c_type'])
                        ->table('comms')
                        ->pagination('50', $num)
                        ->where($where)
                        ->orderby('created desc')
                        ->getall();
                    break;
                    case 'ban':
                        $info = $this->select(['*','type AS b_type'])
                        ->table('bans')
                        ->pagination('50', $num)
                        ->where($where)
                        ->orderby('created desc')
                        ->getall();
                    break;
                    case 'gag':
                        $info = $this->select(['*','type AS с_type'])
                        ->table('comms')
                        ->where('type', '2')
                        ->orwhere('type', '3')
                        ->pagination('50', $num)
                        ->orderby('created desc')
                        ->getall();
                    break;
                }
            }
        } else {
            if($type == 'all') {
                $info = array_merge($this->select(['*','type AS c_type'])
                        ->table('comms')
                        ->pagination('25', $num)
                        ->orderby('created desc')
                        ->getall()
                        ,
                        $this->select(['*','type AS b_type'])
                        ->table('bans')
                        ->pagination('25', $num)
                        ->orderby('created desc')
                        ->getall());
            } else {
                switch($type) {
                    case 'mute':
                        $info = $this->select(['*','type AS c_type'])
                        ->table('comms')
                        ->pagination('50', $num)
                        ->orderby('created desc')
                        ->getall();
                    break;
                    case 'ban':
                        $info = $this->select(['*','type AS b_type'])
                        ->table('bans')
                        ->pagination('50', $num)
                        ->orderby('created desc')
                        ->getall();
                    break;
                    case 'gag':
                        $info = $this->select(['*','type AS с_type'])
                        ->table('comms')
                        ->where('type', '2')
                        ->orwhere('type', '3')
                        ->pagination('50', $num)
                        ->orderby('created desc')
                        ->getall();
                    break;
                }
            } 
        }
        if(empty($info)) return 'Информация пуста';
        usort($info, function($a, $b){
            return strcmp($b->created, $a->created);
        });
        for($i = 0; $i < sizeof($info); $i++) 
        {
            if(isset($info[$i]->b_type)):
                $info[$i]->type = $this->GetType($info[$i]->b_type, 'ban');
                $icon = 'ban';
                $typeid = $info[$i]->b_type;
            else:
                $info[$i]->type = $this->GetType($info[$i]->type, 'mute');
                $icon = 'mute';
                $typeid = $info[$i]->type;
            endif;

            if(isset($_SESSION['user_admin']))
            { 
                $delete = "<a class=btn onclick=SendAjaxSB('','del_ban','{$info[$i]->bid}','{$typeid}','{$icon}');><i class='zmdi zmdi-delete'></i></a>";
                $type   = isset($info[$i]->b_type) ? 'bid' : 'cid';
                $edit   = "<a class=btn href=/sb/?".$type."=".$info[$i]->bid."><i class='zmdi zmdi-edit'></i></a>";
            }
            else
            { 
                $delete = '';
                $edit   = '';
            }
            $result[] = ['id' => $info[$i]->bid, 'icon' => $icon, 'steam' => $info[$i]->authid, 'name' => $info[$i]->name, 'reason' => $info[$i]->reason, 'length' => $this->GetLength($info[$i]), 'created' => $this->GetNormalDate($info[$i]->created), 'type' => $info[$i]->type, 'delete' => $delete, 'edit' => $edit];
        }
        if(empty($result)) return 'Ошиб очка'; 
        else return $result;
    }

    /**
     * Удаление блокировки
     *
     * @since 0.2
     * 
     * @return array           Возвращает результат.
     */
    public function DelBan() {
        !isset($_SESSION['user_admin']) && die();
        if(empty($_POST['param1'])) return ['error' => 'Пустое значение блеат'];
        switch ($_POST['param3']) {
            case 'ban':
                $this->table('bans')->where('bid', $_POST['param1'])->delete();
                return ['success' => 'Бан удален!'];
            break;
            case 'mute':
                $this->table('comms')->where('bid', $_POST['param1'])->delete();
                return ['success' => 'Мут удален!'];
            break;
        }
    }

    /**
     * Поиск блокировок
     *
     * @since 0.2
     * 
     * @return array           Возвращает результат.
     */
    public function SearchBans() 
    {
        if($_POST['type'] != 'all' && $_POST['type'] != 'mute' && $_POST['type'] != 'ban' && $_POST['type'] != 'gag') return ['error' => 'Неправильный тип'];
        $steam = $this->Get32($_POST['steam'], 1);
        $num = intval($_POST['startpagination']);
        $type = $_POST['type'];
        if(empty($steam) && empty($_POST['steam'])) 
        {
            if($_POST['server'] != 'all') 
            {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->orderby('created desc')
                            ->getall()
                             + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->orderby('created desc')
                            ->getall();
                } else {
                    $where = [
                        'sid' => (int) $_POST['server']
                    ];
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->where($where)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->where($where)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where($where)
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }
                }
            } else {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall()
                             + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                } else {
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->where('type', '1')
                            ->orwhere('type', '3')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }
                } 
            }
        } elseif(empty($steam) && !empty($_POST['steam'])) {
            if($_POST['server'] != 'all') 
            {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->like('name', '%'.$_POST['steam'].'%')
                            ->orderby('created desc')
                            ->getall() 
                            + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->like('name', '%'.$_POST['steam'].'%')
                            ->orderby('created desc')
                            ->getall();
                } else {
                    $where = [
                        'sid' => (int) $_POST['server']
                    ];
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->where('type', '1')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }
                }
            } else {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->like('name', '%'.$_POST['steam'].'%')
                            ->orderby('created desc')
                            ->getall()
                             + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->like('name', '%'.$_POST['steam'].'%')
                            ->orderby('created desc')
                            ->getall();
                } else {
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->where('type', '1')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$_POST['steam'].'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }
                } 
            }
        } elseif(!empty($steam)) {
            if($_POST['server'] != 'all') 
            {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->like('authid', '%'.$steam.'%')
                            ->orderby('created desc')
                            ->getall() 
                            + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->where('sid', (int) $_POST['server'])
                            ->like('authid', '%'.$steam.'%')
                            ->orderby('created desc')
                            ->getall();
                } else {
                    $where = [
                        'sid' => (int) $_POST['server']
                    ];
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->where('type', '1')
                            ->where($where)
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->where($where)
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where($where)
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }

                }
            } else {
                if($_POST['type'] == 'all') {
                    $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->pagination('50', $num)
                            ->like('authid', '%'.$steam.'%')
                            ->orderby('created desc')
                            ->getall()
                             + 
                            $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->pagination('50', $num)
                            ->like('authid', '%'.$steam.'%')
                            ->orderby('created desc')
                            ->getall();
                } else {
                    switch($type) {
                        case 'mute':
                            $check = $this->select(['*','type AS c_type'])
                            ->table('comms')
                            ->where('type', '1')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'ban':
                            $check = $this->select(['*','type AS b_type'])
                            ->table('bans')
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                        case 'gag':
                            $check = $this->select(['*','type AS с_type'])
                            ->table('comms')
                            ->where('type', '2')
                            ->orwhere('type', '3')
                            ->like('authid', '%'.$steam.'%')
                            ->pagination('50', $num)
                            ->orderby('created desc')
                            ->getall();
                        break;
                    }
                } 
            }
        }
        if(empty($check)) return 'Информация пуста';
        usort($check, function($a, $b){
            return strcmp($b->created, $a->created);
        });
        for($i = 0; $i < sizeof($check); $i++) 
        {
            if(isset($check[$i]->b_type)):
                $check[$i]->type = $this->GetType($check[$i]->b_type, 'ban');
                $icon = 'ban';
                $typeid = $check[$i]->c_type;
            elseif(isset($check[$i]->c_type)):
                $check[$i]->type = $this->GetType($check[$i]->c_type, 'mute');
                $icon = 'mute';
                $typeid = $check[$i]->c_type;
            endif;

            if(isset($_SESSION['user_admin']))
            { 
                $delete = "<a class=btn onclick=SendAjaxSB('','del_ban','{$check[$i]->bid}','{$typeid}','{$icon}');><i class='zmdi zmdi-delete'></i></a>";
                $type   = $check[$i]->type == 0 ? 'bid' : 'cid';
                $edit   = "<a class=btn href=/sb/?".$type."=".$check[$i]->bid."><i class='zmdi zmdi-edit'></i></a>";
            }
            else
            { 
                $delete = '';
                $edit   = '';
            }

            if(empty($_POST['expired'])) 
            {
                if (time() <= $check[$i]->ends || $check[$i]->length == '0') 
                {
                    $res[] = ['id' => $check[$i]->bid, 'icon' => $icon, 'steam' => $check[$i]->authid, 'name' => $check[$i]->name, 'reason' => $check[$i]->reason, 'length' => $this->GetLength($check[$i]), 'created' => $this->GetNormalDate($check[$i]->created), 'type' => $check[$i]->type, 'delete' => $delete, 'edit' => $edit];
                }
            } 
            else 
            {
                $res[] = ['id' => $check[$i]->bid, 'icon' => $icon, 'steam' => $check[$i]->authid, 'name' => $check[$i]->name, 'reason' => $check[$i]->reason, 'length' => $this->GetLength($check[$i]), 'created' => $this->GetNormalDate($check[$i]->created), 'type' => $check[$i]->type, 'delete' => $delete, 'edit' => $edit];
            }
        }
        if(empty($res)) return 'Информация пуста';
        return $res;
    }

    /**
     * Поиск аватарки
     *
     * @since 0.2
     * 
     * @param int $link             STEAMID короче
     * 
     * @param int $type             В душе не ебу
     * 
     * @return array | boolean      Возвращает результат.
     */
    public function LoadProfile($link, $type = 0)
    {
        $_SAPIKEY = $this->General->arr_general['web_key'];
        $steam = $this->Get32($link, 0);
        if(preg_match('/^STEAM_[0-9]{1,2}:[0-1]:\d+$/', $steam)) 
        {
            $_s64 = con_steam32to64($steam);
            $get = $this->CurlSend("https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=".$_SAPIKEY."&steamids=".$_s64);
            $content = json_decode($get, true);
        }
        if(!empty($content)) 
        {
            if(!empty($type)) return con_steam64to32($content['response']['players'][0]['steamid']);
            else exit (json_encode(array('img' => $content['response']['players'][0]['avatarfull'],)));
        } 
        else 
        {
            if(!empty($type)) return $link;
            else return false;
        }
    }

    /**
     * Информация с настроек
     *
     * @since 0.2
     * 
     * @return array         Возвращает результат.
     */
    public function GetSettings() {
        return require_once MODULES . 'module_page_sb/temp/config.php';
    }

    /**
     * Добавление группы
     *
     * @since 0.2
     * 
     * @return array         Возвращает результат.
     */
    public function AddGroup() {
        !isset($_SESSION['user_admin']) && die();
        if(empty($_POST['imm']) || empty($_POST['name']) || empty($_POST['flags'])) return ['error' => 'Введены не все значения!'];
        $data = [
            'flags' => implode($_POST['flags']),
            'immunity' => $_POST['imm'],
            'name' => $_POST['name'],
            'groups_immune' => ''
        ];
        $this->table('srvgroups')->insert($data);
        return ['success' => 'Группа ' . $_POST['name'] . ' успешно создана!' ];
    }

    /**
     * Удаление группы
     *
     * @since 0.2
     * 
     * @return array         Возвращает результат.
     */
    public function DeleteGroup() {
        !isset($_SESSION['user_admin']) && die();
        if(empty($this->GetGroup($_POST['param1']))) return ['error' => 'Группа не существует'];
        $this->table('srvgroups')->where('id', $_POST['param1'])->delete();
        return ['success' => 'Группа удалена!'];
    }

    /**
     * Изменение группы
     *
     * @since 0.2
     * 
     * @return array         Возвращает результат.
     */
    public function EditGroup() {
        !isset($_SESSION['user_admin']) && die();
        if(empty($this->GetGroup($_POST['param1']))) return ['error' => 'Группа не существует'];
        if(empty($_POST['imm']) || empty($_POST['name']) || empty($_POST['flags'][$_POST['param1']])) return ['error' => 'Введены не все значения!'];
        $data = [
            'flags' => implode($_POST['flags'][$_POST['param1']]),
            'immunity' => $_POST['imm'],
            'name' => $_POST['name']
        ];
        $this->table('srvgroups')->where('id', $_POST['param1'])->update($data);
        return ['success' => 'Группа изменена!'];
    }

    /**
     * Получение кол - ва админов.
     *
     * @since 0.2
     *
     * @return int            Их количество
     */
    public function CountAdmins() {
        return intval(sizeof($this->table('admins')->where('user', '!=', 'CONSOLE')->getall()));
    }

    /**
     * Получение банов игрока
     *
     * @since 0.2
     * 
     * @param string   $steamid32         STEAMID пользователя
     * 
     * @return array   Возвращает результат.
     */
    public function GetBans($steamid32) {
        //Блять, лень пиздец
    }

    /**
     * Проверка на существование сервера
     *
     * @since 0.2
     * 
     * @param int $sid         Айди сервера
     * 
     * @return boolean         Возвращает результат.
     */
    public function CheckServerID($sid) 
    {
        !isset($_SESSION['user_admin']) && die();
        $check = $this->table('servers')->where('sid', $sid)->get();
        if(!empty($check)) return $check;
    }

    /**
     * Обновление блокировки.
     *
     * @since 0.2
     * 
     * @return array            Возвращает результат.
     */
    public function UpdateBan() {
        !isset($_SESSION['user_admin']) && die();
        $steam = $this->Get32($_POST['steam'], 0);
        empty($steam) && ['error' => 'Нормальный STEAMID укажи.'];
        if($_POST['reason'] == 'my')
            $reason = $_POST['myreason'];
        else
            $reason = $_POST['reason'];
        if($_POST['banlength'] != '0')
            $lengthp = $_POST['banlength'] * 60;
        if($_POST['param2'] == '0') {
            $info = $this->table('bans')->where('bid', $_POST['param1'])->get();
            $length = $info->ends - $info->length;
            $length += $lengthp;
            $data = [
                'authid'    => $steam,
                'reason'    => $reason,
                'ends'      => $length,
                'length'    => $lengthp,
                'sid'       => $_POST['server']
            ];
            $this->table('bans')->where('bid', $_POST['param1'])->update($data);
            return ['success' => 'Бан обновлен!'];
        } else {
            $info = $this->table('comms')->where('bid', $_POST['param1'])->get();
            $length = $info->ends - $info->length;
            $length += $lengthp;
            $data = [
                'authid'    => $steam,
                'reason'    => $reason,
                'ends'      => $length,
                'length'    => $lengthp,
                'sid'       => $_POST['server']
            ];
            $this->table('comms')->where('bid', $_POST['param1'])->update($data);
            return ['success' => 'Мут обновлен!'];
        }
    }

    /**
     * Вычесть кол - во дней
     *
     * @since 0.2
     * 
     * @param int $value       Секунд
     * @param int $length      Сверить
     * 
     * @return boolean         Возвращает результат.
     */
    public function GetBanLength($value, $length) {
        if($value == floor($length / 60)) return true;
    }

    /**
     * Отправка CURL запроса
     *
     * @since 0.2
     * 
     * @param int $url         URL сайта
     * 
     * @return array           Возвращает результат.
     */
    public function CurlSend($url) 
    {
		$c = curl_init($url);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		$url = curl_exec($c);
        return $url;
    }
}