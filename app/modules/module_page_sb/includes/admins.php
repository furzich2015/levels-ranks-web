<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
	<div class="col-md-12">
		<div class="card">
			<div class="card-header">
				<ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
					<li id="home" class="change active-ma"><a> Список администраторов</a></li>
					<li id="add" class="change"><a> Добавить админа</a></li>
				</ul>
			</div>
			<div class="target" id="home1">
				<div class="card-header">
					<h2>Список администраторов (<?php echo ceil($sb->CountAdmins()); ?>)</h2>
                    <small>Нажмите на нужного вам администратора в таблице, чтобы узнать больше информации о нем.</small>
                    <div class="select-panel select-panel-pages badge">
                        <select onChange="window.location.href=this.value">
                            <option style="display:none" value="" disabled selected><?php echo $page_num ?></option>
                            <?php for ($v = 0; $v < $page_max; $v++) { ?>
                            <option value="<?php echo set_url_section(get_url(2), 'num', $v + 1) ?>">
                                <a href="<?php echo set_url_section(get_url(2), 'num', $v + 1) ?>"><?php echo $v + 1 ?></a></option>
                            <?php } ?>
                        </select>
                    </div>
				</div>
                    <?php if(!empty($admins)): ?>
                    <table class="research table table-hover">
                        <thead>
                            <tr>
                                <th class="text-center">Имя</th>
                                <th class="text-center">Серверные флаги</th>
                                <th class="text-center">Истекает</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php for($ad = 0; $ad < sizeof($admins); $ad++): $stats = $sb->GetStats($admins[$ad]->aid); ?>
                            <tr id="<?php echo $ad ?>" class="pointer hover-tb">
                                <th class="text-center"><?php echo $admins[$ad]->user ?></th>
                                <th class="text-center"><?php echo $admins[$ad]->srv_flags ?></th>
                                <th class="text-center"><?php echo $sb->GetNormalDate($admins[$ad]->expired); ?></th>
                            </tr>
                            <!-- Давай посмеемся вместе, прикинь, это код скопированный из MA -->
                            <tr class="dop">
                                <td colspan="4" style="padding: 0px;">
                                    <div class="opener" id="<?php echo $ad ?>a">
                                        <div class="p-20">
                                            <div class="card" id="profile-main">
                                                <div class="pm-overview">
                                                    <div>
                                                        <div>
                                                            <div class="pmo-pic">
                                                                <div class="p-relative">
                                                                    <a href="#">
                                                                    <img src="<?php echo $General->getAvatar(con_steam32to64($admins[$ad]->authid), 1) ?>" class="mCS_img_loaded">
                                                                    </a>
                                                                    <a href="https://steamcommunity.com/profiles/<?php echo con_steam32to64($admins[$ad]->authid) ?>" class="pmop-edit" target="_blank">
                                                                    <i class="zmdi zmdi-steam"></i> <span class="hidden-xs">Профиль стима</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                            <div class="pmo-block pmo-contact">
                                                                <h2>Связь</h2>
                                                                <ul>
                                                                    <li><i class="zmdi zmdi-steam"></i><?php echo $admins[$ad]->authid ?></li>
                                                                    <li><i class="zmdi zmdi-account-box-o"></i> <?php echo $stats['info'][0]->skype ?: 'Нет данных'; ?></li>
                                                                    <li><i class="zmdi zmdi-vk"></i> <?php echo $stats['info'][0]->vk ?: 'Нет данных'; ?></li>
                                                                </ul>
                                                            </div>
                                                            <div class="pmo-block">
                                                                <h2>Права</h2>
                                                                <a class="btn" data-toggle="modal" href="#admin_<?php echo $admins[$ad]->aid ?>">
                                                                Серверные
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="pm-body">
                                                    <ul class="tab-nav">
                                                        <li><a href="/sb/?c=details&aid=<?php echo $admins[$ad]->aid ?>">Изменить информацию</a></li>
                                                        <li><a onclick="if(confirm('Вы действительно хотите удалить его?')) { SendAjaxSB('', 'del_admin', '<?php echo $admins[$ad]->aid ?>', '', ''); }">Удалить</a></li>
                                                    </ul>
                                                    <div class="pmb-block p-t-30">
                                                        <div class="pmbb-header">
                                                            <h2><i class="zmdi zmdi-comment m-r-5"></i> Комментарий</h2>
                                                        </div>
                                                        <div class="pmbb-body p-l-30">
                                                            <div class="pmbb-view">
                                                                <?php echo $stats['info'][0]->comment ?: 'Нет доступных комментариев.'; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pmb-block p-t-10">
                                                        <div class="pmbb-header">
                                                            <h2><i class="zmdi zmdi-hourglass-alt m-r-5"></i> Визит и срок</h2>
                                                        </div>
                                                        <div class="pmbb-body p-l-30">
                                                            <div class="pmbb-view">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Дата окончания</dt>
                                                                    <dd><?php echo $sb->GetNormalDate($admins[$ad]->expired); ?></dd>
                                                                </dl>
                                                                <dl class="dl-horizontal">
                                                                    <dt>Последний визит</dt>
                                                                    <dd><?php echo $sb->GetNormalDate($admins[$ad]->lastvisit); ?></dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="pmb-block p-t-10">
                                                        <div class="pmbb-header">
                                                            <h2><i class="zmdi zmdi-fire m-r-5"></i> Баны / Муты</h2>
                                                        </div>
                                                        <div class="pmbb-body p-l-30">
                                                            <div class="pmbb-view">
                                                                <dl class="dl-horizontal">
                                                                    <dt>Баны</dt>
                                                                    <dd><?php echo $stats['bans'] ?></dd>
                                                                </dl>
                                                                <dl class="dl-horizontal">
                                                                    <dt>Муты</dt>
                                                                    <dd><?php echo $stats['comms'] ?></dd>
                                                                </dl>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <div id="admin_<?php echo $admins[$ad]->aid ?>" class="modal-window">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="badge">Права админа <?php echo $admins[$ad]->user ?></h5>
                                            <a href="##" title="Close" class="modal-close badge"><i class="zmdi zmdi-close zmdi-hc-fw"></i></a>
                                        </div>
                                        <div class="card-container">
                                            <?php 
                                                if($sb->CheckFlag('z', $admins[$ad]->aid)) echo "Полный доступ<br>";
                                                if($sb->CheckFlag('a', $admins[$ad]->aid)) echo "Резервный доступ<br>";
                                                if($sb->CheckFlag('b', $admins[$ad]->aid)) echo "Рядовой флаг администратора<br>";
                                                if($sb->CheckFlag('c', $admins[$ad]->aid)) echo "Кик других игроков<br>";
                                                if($sb->CheckFlag('d', $admins[$ad]->aid)) echo "Бан других игроков<br>";
                                                if($sb->CheckFlag('e', $admins[$ad]->aid)) echo "Разбан игроков<br>";
                                                if($sb->CheckFlag('f', $admins[$ad]->aid)) echo "Убить / нанести вред игроку<br>";
                                                if($sb->CheckFlag('g', $admins[$ad]->aid)) echo "Изменение карт<br>";
                                                if($sb->CheckFlag('h', $admins[$ad]->aid)) echo "Изменение кваров<br>";
                                                if($sb->CheckFlag('i', $admins[$ad]->aid)) echo "Выполнение конфигов<br>";
                                                if($sb->CheckFlag('j', $admins[$ad]->aid)) echo "Привилегии в чате<br>";
                                                if($sb->CheckFlag('k', $admins[$ad]->aid)) echo "Управление голосованиями<br>";
                                                if($sb->CheckFlag('l', $admins[$ad]->aid)) echo "Установка пароля на сервер<br>";
                                                if($sb->CheckFlag('m', $admins[$ad]->aid)) echo "Выполнение RCON команд<br>";
                                                if($sb->CheckFlag('n', $admins[$ad]->aid)) echo "Изменение sv_cheats";
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            <?php endfor; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
			</div>
            <div class="target" id="add1">
                <div class="card-header">
					<h2>Добавление админа</h2>
					<small>Тут можно добавить любого администратора мира.</small>
				</div>
                <form method="post" id="add_admin" onsubmit="SendAjaxSB('#add_admin', 'add_admin', '', '', ''); return false;">
                    <div id="profile-main">
                        <div class="pm-overview">
                            <div class="pmo-pic">
                                <div class="p-relative">
                                    <a id="img_profile" href="#">
                                        <img src="<?php echo $General->getAvatar(123, 1) ?>">
                                    </a>
                                    <div class="input-form"><div class="input_text">Логин</div><input name="login" required></div>
                                    <div class="input-form"><div class="input_text">STEAMID</div><input name="steam" id="steam" required></div>
                                    <div class="input-form"><div class="input_text">Skype</div><input name="skype" placeholder="Не обязательно"></div>
                                    <div class="input-form"><div class="input_text">VK</div><input name="vk" placeholder="Не обязательно"></div>
                                    <div class="input-form"><div class="input_text">Комментарий</div><input name="comment" placeholder="Не обязательно"></div>
                                    <div class="input-form">
                                        <div class="input_text">Дата окончания</div>
                                        <select id="date" name="date" tabindex="4">
                                            <option value="0"0>Навсегда</option>
                                            <optgroup class="selectdate" label="Минуты">
                                                <option value="1">1 минута</option>
                                                <option value="5">5 минут</option>
                                                <option value="10">10 минут</option>
                                                <option value="15">15 минут</option>
                                                <option value="30">30 минут</option>
                                                <option value="45">45 минут</option>
                                            </optgroup>
                                            <optgroup class="selectdate" label="Часы">
                                                <option value="60">1 час</option>
                                                <option value="120">2 часа</option>
                                                <option value="180">3 часа</option>
                                                <option value="240">4 часа</option>
                                                <option value="480">8 часов</option>
                                                <option value="720">12 часов</option>
                                            </optgroup>
                                            <optgroup class="selectdate" label="Дни">
                                                <option value="1440">1 день</option>
                                                <option value="2880">2 дня</option>
                                                <option value="4320">3 дня</option>
                                                <option value="5760">4 дня</option>
                                                <option value="7200">5 дней</option>
                                                <option value="8640">6 дней</option>
                                            </optgroup>
                                            <optgroup class="selectdate" label="Недели">
                                                <option value="10080">1 неделя</option>
                                                <option value="20160">2 недели</option>
                                                <option value="30240">3 недели</option>
                                            </optgroup>
                                            <optgroup class="selectdate" label="Месяцы">
                                                <option value="43200">1 месяц</option>
                                                <option value="86400">2 месяца</option>
                                                <option value="129600">3 месяца</option>
                                                <option value="259200">6 месяцев</option>
                                                <option value="518400">12 месяцев</option>
                                            </optgroup>
                                        </select>
                                    </div>
                                    <div class="input-form">
                                        <input class="border-checkbox" type="checkbox" name="enabled_support" id="enabled_support">
                                        <label class="border-checkbox-label" for="enabled_support">Имеет ли доступ к апелляциям?</label>
                                    </div>
                                </div>
                                <input type="submit" value="Создать!" class="btn">
                            </div>
                        </div>
                        <div class="pm-body">
                            <ul class="tab-nav">
                                <li><a href="#access">Права</a></li>
                            </ul>
                            <div class="pmb-block">
                                <div class="pmbb-header">
                                    <h2><i class="zmdi zmdi-assignment"></i> Сервера</h2>
                                </div>
                                <div class="p-l-30">
                                    <div class="pmbb-view">
                                        <?php for($i = 0; $i < sizeof($servers); $i++) { ?>
                                            <div class="input-form">
                                                <input class="border-checkbox" value="<?php echo $servers[$i]->sid ?>" type="checkbox" name="servers[]" id="<?php echo $servers[$i]->sid ?>server">
                                                <label class="border-checkbox-label" for="<?php echo $servers[$i]->sid ?>server"><?php echo $servers[$i]->ip . ':' . $servers[$i]->port; ?></label>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-bottom">
                        <?php if( $page_max != 1):?>
                            <div class="select-panel-pages">
                                <?php endif; if ( $page_num != 1) { ?>
                                    <a href="<?php echo set_url_section(get_url(2), 'num', $page_num - 1) ?>"><h5
                                                class="badge"><?php $General->get_icon('zmdi', 'chevron-left') ?></h5></a>
                                <?php } ?>
                                <?php if ( $page_num != $page_max) { ?>
                                    <a href="<?php echo set_url_section(get_url(2), 'num', $page_num + 1) ?>"><h5
                                                class="badge"><?php $General->get_icon('zmdi', 'chevron-right') ?></h5></a>
                                <?php } if( $page_max != 1):?>
                            </div>
                        <?php endif;?>
                    </div>
                </div>
            </div>
        </div>
        <div id="access" class="modal-window">
            <div class="card">
                <div class="card-header">
                    <h5 class="badge">Выбрать права админа</h5>
                    <a href="##" title="Close" class="modal-close badge"><i class="zmdi zmdi-close zmdi-hc-fw"></i></a>
                </div>
                <div class="card-container">
                    <div class="input-form">
                        <label>Выбор прав</label>
                        <select onchange="ChangeBlock()" name="selectgroup" id="selectgroup">
                            <option value="select">Выборочные права</option>
                            <?php if(!empty($groups)): for($i = 0; $i < sizeof($groups); $i++): ?>
                                <option value="<?php echo $groups[$i]->id ?>"><?php echo $groups[$i]->name ?></option>
                            <?php endfor; endif;?>
                        </select>
                    </div>
                    <div id="selectflags">
                        <div class="input-form">
                            <div class="input_text">Иммунитет</div>
                            <input name="immune">
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="z" type="checkbox" name="flags[z]" id="z">
                            <label class="border-checkbox-label" for="z">Флаг z - Полный доступ</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="a" type="checkbox" name="flags[a]" id="a">
                            <label class="border-checkbox-label" for="a">Флаг а - Резервный слот</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="b" type="checkbox" name="flags[b]" id="b">
                            <label class="border-checkbox-label" for="b">Флаг b - Рядовой флаг администратора</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="c" type="checkbox" name="flags[c]" id="c">
                            <label class="border-checkbox-label" for="c">Флаг c - Кик других игроков</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="d" type="checkbox" name="flags[d]" id="d">
                            <label class="border-checkbox-label" for="d">Флаг d - Бан других игроков</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="e" type="checkbox" name="flags[e]" id="e">
                            <label class="border-checkbox-label" for="e">Флаг e - Разбан игроков</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="f" type="checkbox" name="flags[f]" id="f">
                            <label class="border-checkbox-label" for="f">Флаг f - Убить / нанести вред игроку</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="g" type="checkbox" name="flags[g]" id="g">
                            <label class="border-checkbox-label" for="g">Флаг g - Изменение карт</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="h" type="checkbox" name="flags[h]" id="h">
                            <label class="border-checkbox-label" for="h">Флаг h - Изменение кваров</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="i" type="checkbox" name="flags[i]" id="i">
                            <label class="border-checkbox-label" for="i">Флаг i - Выполнение конфигов</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="j" type="checkbox" name="flags[j]" id="j">
                            <label class="border-checkbox-label" for="j">Флаг j - Привилегии в чате</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="k" type="checkbox" name="flags[k]" id="k">
                            <label class="border-checkbox-label" for="k">Флаг k - Управление голосованиями</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="l" type="checkbox" name="flags[l]" id="l">
                            <label class="border-checkbox-label" for="l">Флаг l - Установка пароля на сервер</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="m" type="checkbox" name="flags[m]" id="m">
                            <label class="border-checkbox-label" for="m">Флаг m - Выполнение RCON команд</label>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" value="n" type="checkbox" name="flags[n]" id="n">
                            <label class="border-checkbox-label" for="n">Флаг n - Изменение sv_cheats</label>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<style>
#input-contact {
    padding: 0;
}
</style>