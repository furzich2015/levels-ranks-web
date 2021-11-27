<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
				<ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
					<li id="home" class="change active-ma"><a> Информация</a></li>
					<li id="edit" class="change"><a> Изменить</a></li>
				</ul>
			</div>
            <div class="target padding-15" id="home1">
                <div class="card-header">
                    <h2>Информация о блокировке ( ID = <?php echo $binfo->bid ?> )</h2>
                    <small>Тут находится подробная информация о блокировке.</small>
                </div>
                <div class="col-sm-7">
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Игрок</label>
                        <div class="col-sm-8 float-left"><?php echo $binfo->name ?: 'Без имени' ?></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Steam ID</label>
                        <div class="col-sm-8 float-left"><a target="_blank" href="http://steamcommunity.com/profiles/<?php echo con_steam32to64($binfo->authid) ?>"><?php echo $binfo->authid ?></a></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Причина</label>
                        <div class="col-sm-8 float-left"><?php echo $binfo->reason ?></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Дата выдачи</label>
                        <div class="col-sm-8 float-left"><?php echo date("d.m.Y в H:i", $binfo->created) ?></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Дата окончания</label>
                        <div class="col-sm-8 float-left">
                        <?php 
                            echo $sb->GetLength($binfo);
                        ?></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Администратор</label>
                        <div class="col-sm-8 float-left">
                            <?php if($binfo->adminIp != 'STEAM_ID_SERVER' && $binfo->aid != '0'): ?>
                                <a target="_blank" href="http://steamcommunity.com/profiles/<?php echo con_steam32to64($binfo->adminIp) ?>"><?php echo $binfo->adminIp ?></a>
                            <?php else: ?>
                                Сервер
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Тип</label>
                        <div class="col-sm-8 float-left"><?php echo $sb->GetType($binfo->type, $typeban);?></div>
                    </div>
                    <div class="col-sm-12">
                        <label class="col-sm-4 float-left"><i class="zmdi zmdi-circle-o text-left"></i> Сервер</label>
                        <div class="col-sm-8 float-left"><?php echo $server->ip . ":" . $server->port; ?></div>
                    </div>
                </div>
            </div>
            <form onsubmit='SendAjaxSB("#update_ban", "update_ban", "<?php echo $binfo->bid ?>", "<?php echo $binfo->type ?>", ""); return false;' id="update_ban" method="post">
                <div class="target" id="edit1">
                    <div class="card-header">
                        <h2>Изменение блокировки ( ID = <?php echo $binfo->bid ?> )</h2>
                        <small>Можете поменять что - то, я хз зачем, но меняйте на здоровье</small>
                    </div>
                    <div class="card-container">
                    <div id="img_profile" class="text-center max-width-img"></div>
                        <div class="input-form">
                            <div class="input_text">STEAM ID</div>
                            <input name="steam" placeholder="<?php echo $binfo->authid ?>" value="<?php echo $binfo->authid ?>" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Причина</div>
                            <select onchange="Reason()" name="reason" id="reason">
                                    <option value="my">Своя причина</option>
                                <?php for($i = 0; $i < sizeof($config['reasons']); $i++): ?>
                                    <option value="<?php echo $config['reasons'][$i] ?>"><?php echo $config['reasons'][$i] ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="input-form" id="myreason">
                            <div class="input_text">Моя причина</div>
                            <input name="myreason" placeholder="<?php echo $binfo->reason ?>" value="<?php echo $binfo->reason ?>" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">На сколько выдано</div>
                            <select id="banlength" name="banlength" tabindex="4" class="selectpicker bs-select-hidden">
                                <option value="0" <?php if($sb->GetBanLength('0', $binfo->length)) echo 'selected=""'; ?>>Навсегда</option>
                                <optgroup class="selectdate" label="Минуты">
                                    <option value="1" <?php if($sb->GetBanLength('1', $binfo->length)) echo 'selected=""'; ?>>1 минута</option>
                                    <option value="5" <?php if($sb->GetBanLength('5', $binfo->length)) echo 'selected=""'; ?>>5 минут</option>
                                    <option value="10" <?php if($sb->GetBanLength('10', $binfo->length)) echo 'selected=""'; ?>>10 минут</option>
                                    <option value="15" <?php if($sb->GetBanLength('15', $binfo->length)) echo 'selected=""'; ?>>15 минут</option>
                                    <option value="30" <?php if($sb->GetBanLength('30', $binfo->length)) echo 'selected=""'; ?>>30 минут</option>
                                    <option value="45" <?php if($sb->GetBanLength('45', $binfo->length)) echo 'selected=""'; ?>>45 минут</option>
                                </optgroup>
                                <optgroup class="selectdate" label="Часы">
                                    <option value="60" <?php if($sb->GetBanLength('60', $binfo->length)) echo 'selected=""'; ?>>1 час</option>
                                    <option value="120" <?php if($sb->GetBanLength('120', $binfo->length)) echo 'selected=""'; ?>>2 часа</option>
                                    <option value="180" <?php if($sb->GetBanLength('180', $binfo->length)) echo 'selected=""'; ?>>3 часа</option>
                                    <option value="240" <?php if($sb->GetBanLength('240', $binfo->length)) echo 'selected=""'; ?>>4 часа</option>
                                    <option value="480" <?php if($sb->GetBanLength('480', $binfo->length)) echo 'selected=""'; ?>>8 часов</option>
                                    <option value="720" <?php if($sb->GetBanLength('720', $binfo->length)) echo 'selected=""'; ?>>12 часов</option>
                                </optgroup>
                                <optgroup class="selectdate" label="Дни">
                                    <option value="1440" <?php if($sb->GetBanLength('1440', $binfo->length)) echo 'selected=""'; ?>>1 день</option>
                                    <option value="2880" <?php if($sb->GetBanLength('2880', $binfo->length)) echo 'selected=""'; ?>>2 дня</option>
                                    <option value="4320" <?php if($sb->GetBanLength('4320', $binfo->length)) echo 'selected=""'; ?>>3 дня</option>
                                    <option value="5760" <?php if($sb->GetBanLength('5760', $binfo->length)) echo 'selected=""'; ?>>4 дня</option>
                                    <option value="7200" <?php if($sb->GetBanLength('7200', $binfo->length)) echo 'selected=""'; ?>>5 дней</option>
                                    <option value="8640" <?php if($sb->GetBanLength('8640', $binfo->length)) echo 'selected=""'; ?>>6 дней</option>
                                </optgroup>
                                <optgroup class="selectdate" label="Недели">
                                    <option value="10080" <?php if($sb->GetBanLength('10080', $binfo->length)) echo 'selected=""'; ?>>1 неделя</option>
                                    <option value="20160" <?php if($sb->GetBanLength('20160', $binfo->length)) echo 'selected=""'; ?>>2 недели</option>
                                    <option value="30240" <?php if($sb->GetBanLength('30240', $binfo->length)) echo 'selected=""'; ?>>3 недели</option>
                                </optgroup>
                                <optgroup class="selectdate" label="Месяцы">
                                    <option value="43200" <?php if($sb->GetBanLength('43200', $binfo->length)) echo 'selected=""'; ?>>1 месяц</option>
                                    <option value="86400" <?php if($sb->GetBanLength('86400', $binfo->length)) echo 'selected=""'; ?>>2 месяца</option>
                                    <option value="129600" <?php if($sb->GetBanLength('129600', $binfo->length)) echo 'selected=""'; ?>>3 месяца</option>
                                    <option value="259200" <?php if($sb->GetBanLength('259200', $binfo->length)) echo 'selected=""'; ?>>6 месяцев</option>
                                    <option value="518400" <?php if($sb->GetBanLength('518400', $binfo->length)) echo 'selected=""'; ?>>12 месяцев</option>
                                </optgroup>
                            </select>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Сервер</div>
                            <select name="server" id="server">
                                <?php for($i = 0; $i < sizeof($servers); $i++): ?>
                                    <option value="<?php echo $servers[$i]->sid ?>" <?php if($servers[$i]->sid == $binfo->sid) echo "selected=''"; ?>><?php echo $servers[$i]->ip . ':' . $servers[$i]->port ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <input type="submit" value="Изменить!" class="btn">
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>