<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
				<ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
					<li id="home" class="change active-ma"><a> Список блокировок</a></li>
					<li id="add" class="change"><a> Добавить блокировку</a></li>
				</ul>
			</div>
            <div class="target" id="home1">
                <div class="card-header">
                    <h2>Список блокировок</h2>
                    <small>В этой невероятной вкладке, вас ждут приключения, в виде забаненных идиотов</small>
                </div>
                <div class="input-form input-search-margin">
                    <label>Сервер</label>
                    <select onchange="ChangeServer(this.value)" name="server" id="server">
                        <?php if(!empty($servers)): ?>
                        <option value="all">Все</option>
                        <?php for($i = 0; $i < sizeof($servers); $i++): ?>
                        <option value="<?php echo $servers[$i]->sid ?>"><?php echo $servers[$i]->ip . ':' . $servers[$i]->port; ?></option>
                        <?php endfor; else: echo '<option disabled>Серверов нет</option>'; endif; ?>
                    </select>
                </div>
                <div class="btn" id="searchmore" style="margin: 15px;">Поиск</div>
                <div style="display: none;" id="searchdiv">
                    <div class="card-container">
                    <div id="img_profile" class="text-center max-width-img"></div>
                        <form onsubmit="return false" id="searchban" method="post">
                            <div class="input-form">
                                <div class="input_text">STEAM ID или Ник</div>
                                <input name="steam" placeholder="STEAM_1:1:12344 ... / 7653453464 ... / [U:1:12345..] ... / Алексей">
                            </div>
                            <div class="input-form">
                                <label>Тип блокировки</label>
                                <select name="type">
                                    <option value="all">Все</option>
                                    <option value="ban">Бан</option>
                                    <option value="mute">Мут</option>
                                    <option value="gag">Гаг</option>
                                </select>
                            </div>
                            <div class="input-form">
                                <input class="border-checkbox" type="checkbox" name="expired" id="expired" checked="">
                                <label class="border-checkbox-label" for="expired">Показывать ли истекшие блокировки?</label>
                            </div>
                            <input type="submit" value="Найти!" class="btn">
                        </form>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center"></th>
                            <th class="text-center">Дата</th>
                            <th class="text-center">Игрок</th>
                            <th class="text-center">Причина</th>
                            <th class="text-center">Срок</th>
                            <th class="text-center"></th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody id="res_bans">
                        <?php for($i = 0; $i < sizeof($bans); $i++): ?>
                            <tr id="<?php echo $i ?>">
                                <th class="text-center"><?php if($bans[$i]['icon'] == 'ban') echo '<i class="zmdi zmdi-block zmdi-hc-fw"></i>'; else echo '<i class="zmdi zmdi-mic-off zmdi-hc-fw"></i>'; ?></th>
                                <th class="text-center"><?php echo $bans[$i]['created'] ?></th>
                                <th class="text-center"><?php echo $bans[$i]['name'] ?></th>
                                <th class="text-center"><?php echo $bans[$i]['reason'] ?></th>
                                <th class="text-center"><?php echo $bans[$i]['length'] ?></th>
                                <th class="text-center"><?php echo $bans[$i]['delete'] ?></th>
                                <th class="text-center"><?php echo $bans[$i]['edit'] ?></th>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <div id="result"></div>
                <div class="card-container">
                    <button class="btn" id="more_ajax">Дальше</button>
                </div> 
            </div>
            <div class="target" id="add1">
                <div class="card-header">
                    <h2>Добавление блокировки</h2>
                    <small>В этом месте, можно добавить бан вашей жертве, чтобы она плакала</small>
                </div>
                <div class="card-container">
                    <div id="img_profile" class="text-center max-width-img"></div>
                    <form method="post" id="add_ban" onsubmit="SendAjaxSB('#add_ban', 'add_ban', '', '', ''); return false;">
                        <div class="input-form">
                            <label>Сервер</label>
                            <select name="sid">
                                <?php if(!empty($servers)): for($i = 0; $i < sizeof($servers); $i++): ?>
                                <option value="<?php echo $servers[$i]->sid ?>"><?php echo $servers[$i]->ip . ':' . $servers[$i]->port; ?></option>
                                <?php endfor; else: echo '<option disabled>Серверов нет</option>'; endif; ?>
                            </select>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Никнейм жертвы</div>
                            <input name="nickname" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">STEAMID жертвы</div>
                            <input name="steam" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Причина</div>
                            <input name="reason" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">Срок в днях</div>
                            <input name="time" required>
                        </div>
                        <div class="input-form">
                            <label>Тип блокировки</label>
                            <select name="type">
                                <option value="ban">Бан</option>
                                <option value="mute">Мут</option>
                                <option value="gag">Гаг</option>
                            </select>
                        </div>
                        <input type="submit" value="Создать!" class="btn">
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>