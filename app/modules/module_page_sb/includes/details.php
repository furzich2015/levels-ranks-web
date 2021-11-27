<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
				<ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
					<li id="home" class="change active-ma"><a> Информация</a></li>
					<li id="servers" class="change"><a> Сервера</a></li>
                    <li id="access" class="change"><a> Доступ</a></li>
				</ul>
			</div>
            <div onload="ChangeBlock()" class="card-container">
                <form onsubmit='SendAjaxSB("#edit_admin", "edit_admin", "<?php echo $_GET["aid"]; ?>", "", ""); return false;' id="edit_admin" method="post">
                    <div class="target" id="home1">
                        <div class="input-form">
                            <div class="input_text">Логин</div>
                            <input name="login" placeholder="<?php echo $info->user ?>" value="<?php echo $info->user ?>" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">STEAM ID</div>
                            <input name="steam" placeholder="<?php echo $info->authid ?>" value="<?php echo $info->authid ?>" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Доступ (0 - навсегда, не трогать - оставить как есть)</div>
                            <input name="date">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Skype</div>
                            <input name="skype" value="<?php echo $info->skype ?>" placeholder="<?php echo $info->skype ?>">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Комментарий</div>
                            <input name="comment" value="<?php echo $info->comment ?>" placeholder="<?php echo $info->comment ?>">
                        </div>
                        <div class="input-form">
                            <div class="input_text">ВКонтакте</div>
                            <input name="vk" value="<?php echo $info->vk ?>" placeholder="<?php echo $info->vk ?>">
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" type="checkbox" name="enabled_support" id="enabled_support">
                            <label class="border-checkbox-label" for="enabled_support">Имеет ли доступ к апелляциям?</label>
                        </div>
                        <input type="submit" value="Изменить!" class="btn">
                    </div>
                    <div class="target" id="servers1">
                        <?php for($i = 0; $i < sizeof($servers); $i++) { ?>
                            <div class="input-form">
                                <input class="border-checkbox" value="<?php echo $servers[$i]->sid ?>" type="checkbox" name="servers[]" id="<?php echo $servers[$i]->sid ?>server" <?php echo $sb->CheckServer($_GET['aid'], $servers[$i]->sid) ?>>
                                <label class="border-checkbox-label" for="<?php echo $servers[$i]->sid ?>server"><?php echo $servers[$i]->ip . ':' . $servers[$i]->port; ?></label>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="target" id="access1">
                        <div class="input-form">
                            <label>Выбор прав</label>
                            <select onchange="ChangeBlock()" name="selectgroup" id="selectgroup">
                                <option value="select">Выборочные права</option>
                                <?php if(!empty($groups)): for($i = 0; $i < sizeof($groups); $i++): ?>
                                    <option value="<?php echo $groups[$i]->id ?>" <?php if($groups[$i]->name == $info->srv_group) echo 'selected=""'; ?>><?php echo $groups[$i]->name ?></option>
                                <?php endfor; endif;?>
                            </select>
                        </div>
                        <div id="selectflags">
                            <div class="input-form">
                                <div class="input_text">Иммунитет</div>
                                <input name="immune" value="<?php echo $info->immunity ?>">
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="z" type="checkbox" name="flags[z]" id="z" <?php if($sb->CheckFlag('z', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="z">Флаг z - Полный доступ</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="a" type="checkbox" name="flags[a]" id="a" <?php if($sb->CheckFlag('a', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="a">Флаг а - Резервный слот</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="b" type="checkbox" name="flags[b]" id="b" <?php if($sb->CheckFlag('b', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="b">Флаг b - Рядовой флаг администратора</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="c" type="checkbox" name="flags[c]" id="c" <?php if($sb->CheckFlag('c', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="c">Флаг c - Кик других игроков</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="d" type="checkbox" name="flags[d]" id="d" <?php if($sb->CheckFlag('d', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="d">Флаг d - Бан других игроков</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="e" type="checkbox" name="flags[e]" id="e" <?php if($sb->CheckFlag('e', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="e">Флаг e - Разбан игроков</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="f" type="checkbox" name="flags[f]" id="f" <?php if($sb->CheckFlag('f', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="f">Флаг f - Убить / нанести вред игроку</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="g" type="checkbox" name="flags[g]" id="g" <?php if($sb->CheckFlag('g', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="g">Флаг g - Изменение карт</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="h" type="checkbox" name="flags[h]" id="h" <?php if($sb->CheckFlag('h', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="h">Флаг h - Изменение кваров</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="i" type="checkbox" name="flags[i]" id="i" <?php if($sb->CheckFlag('i', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="i">Флаг i - Выполнение конфигов</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="j" type="checkbox" name="flags[j]" id="j" <?php if($sb->CheckFlag('j', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="j">Флаг j - Привилегии в чате</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="k" type="checkbox" name="flags[k]" id="k" <?php if($sb->CheckFlag('k', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="k">Флаг k - Управление голосованиями</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="l" type="checkbox" name="flags[l]" id="l" <?php if($sb->CheckFlag('l', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="l">Флаг l - Установка пароля на сервер</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="m" type="checkbox" name="flags[m]" id="m" <?php if($sb->CheckFlag('m', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="m">Флаг m - Выполнение RCON команд</label>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" value="n" type="checkbox" name="flags[n]" id="n" <?php if($sb->CheckFlag('n', $_GET['aid'])) echo 'checked'; ?>>
                                <label class="border-checkbox-label" for="n">Флаг n - Изменение sv_cheats</label>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>