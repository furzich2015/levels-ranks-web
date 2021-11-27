<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
                    <li id="home" class="change active-ma"><a> Список серверов</a></li>
                    <li id="add" class="change"><a> Добавить сервер</a></li>
                </ul>
            </div>
            <div class="target" id="add1">
                <div class="card-header">
					<h2>Добавление сервера</h2>
					<small>Тут можно добавить ваш супер пупер сервер, чтобы потом он тут был.</small>
				</div>
                <div class="card-container">
                    <form onsubmit='SendAjaxSB("#add_server", "add_server", "", "", ""); return false;' id="add_server" method="post">
                        <div class="input-form">
                            <div class="input_text">IP</div>
                            <input name="ip" placeholder="192.0.0.1" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">PORT</div>
                            <input name="port" placeholder="27015" required>
                        </div>
                        <div class="input-form">
                            <div class="input_text">RCON</div>
                            <input name="rcon" type="password" required>
                        </div>
                        <div class="input-form">
                            <input class="border-checkbox" type="checkbox" name="enabled" id="enabled" checked="">
                            <label class="border-checkbox-label" for="enabled">Включен?</label>
                        </div>
                        <input type="submit" value="Создать!" class="btn">
                    </form>
                </div>
            </div>
            <div class="target" id="home1">
                <div class="card-header">
					<h2>Список серверов (<?php echo sizeof($servers); ?>)</h2>
                    <small>Нажмите на сервер чтобы его изменить.</small>
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
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th class="text-center">SID</th>
                            <th class="text-center">IP</th>
                            <th class="text-center">PORT</th>
                            <th class="text-center">RCON</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0; $i < sizeof($serversp); $i++): ?>
                            <tr id="<?php echo $i ?>" class="pointer hover-tb">
                                <th class="text-center"><?php echo $serversp[$i]->sid ?></th>
                                <th class="text-center"><?php echo $serversp[$i]->ip ?></th>
                                <th class="text-center"><?php echo $serversp[$i]->port ?></th>
                                <th class="text-center"><?php echo $serversp[$i]->rcon ?></th>
                                <th class="text-center"><button class="remove_object" onclick="SendAjaxSB('', 'del_server', '<?php echo $serversp[$i]->sid ?>', '', '')"><i class="zmdi zmdi-close zmdi-hc-fw"></i></button></th>
                            </tr>
                            <tr class="dop">
                                <td colspan="4" style="padding: 0px;border-top: 0px solid #FFFFFF;">
                                    <div class="opener" id="<?php echo $i ?>a">
                                        <div class="card-container">
                                            <form onsubmit="SendAjaxSB('#edit_server<?php echo $serversp[$i]->sid ?>', 'edit_server', '<?php echo $serversp[$i]->sid ?>', '', ''); return false;" id="edit_server<?php echo $serversp[$i]->sid ?>" method="post">
                                                <div class="input-form">
                                                    <div class="input_text">IP</div>
                                                    <input name="ip" placeholder="<?php echo $serversp[$i]->ip ?>" value="<?php echo $serversp[$i]->ip ?>" required>
                                                </div>
                                                <div class="input-form">
                                                    <div class="input_text">PORT</div>
                                                    <input name="port" placeholder="<?php echo $serversp[$i]->port ?>" value="<?php echo $serversp[$i]->port ?>" required>
                                                </div>
                                                <div class="input-form">
                                                    <div class="input_text">RCON</div>
                                                    <input name="rcon" type="password" placeholder="<?php echo $serversp[$i]->rcon ?>" value="<?php echo $serversp[$i]->rcon ?>" required>
                                                </div>
                                                <div class="input-form">
                                                    <input class="border-checkbox" type="checkbox" name="enabled" id="enabled<?php echo $serversp[$i]->sid ?>" <?php if($serversp[$i]->enabled == '1') { ?> checked="" <?php } ?>>
                                                    <label class="border-checkbox-label" for="enabled<?php echo $serversp[$i]->sid ?>">Включен?</label>
                                                </div>
                                                <input type="submit" value="Изменить!" class="btn">
                                            </form>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
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
</div>