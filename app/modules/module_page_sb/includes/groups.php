<?php !isset($_SESSION['user_admin']) && die(); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <ul class="tab-nav text-center fw-nav">
                    <li class="change float-left"><a href="javascript:history.go(-1);" onclick=""> Назад</a></li>
                    <li id="home" class="change active-ma"><a> Список групп</a></li>
                    <li id="add" class="change"><a> Добавить группу</a></li>
                </ul>
            </div>
            <div class="target" id="home1">
                <div class="card-header">
                    <h2>Список групп (<?php echo ceil($sb->CountGroups()); ?>)</h2>
                    <small>Можете тут изменить или удалить группу.</small>
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
                        <tr id="pointer">
                            <th class="text-center">Имя</th>
                            <th class="text-center">Флаги</th>
                            <th class="text-center">Иммунитет</th>
                            <th class="text-center"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php for($i = 0; $i < sizeof($groups); $i++): ?>
                            <tr id="<?php echo $i ?>" class="pointer hover-tb">
                                <th class="text-center"><?php echo $groups[$i]->name ?></th>
                                <th class="text-center"><?php echo $groups[$i]->flags ?></th>
                                <th class="text-center"><?php echo $groups[$i]->immunity ?></th>
                                <th class="text-center"><button class="remove_object" onclick="SendAjaxSB('', 'del_group', '<?php echo $groups[$i]->id ?>', '', '')"><i class="zmdi zmdi-close zmdi-hc-fw"></i></button></th>
                            </tr>
                            <tr class="dop">
                                <td colspan="4" style="padding: 0px;border-top: 0px solid #FFFFFF;">
                                    <div class="opener" id="<?php echo $i ?>a">
                                        <div class="card-container">
                                            <form onsubmit="SendAjaxSB('#edit_group<?php echo $groups[$i]->id ?>', 'edit_group', '<?php echo $groups[$i]->id ?>', '', ''); return false;" id="edit_group<?php echo $groups[$i]->id ?>" method="post">
                                                <div class="input-form">
                                                    <div class="input_text">Имя</div>
                                                    <input name="name" placeholder="<?php echo $groups[$i]->name ?>" value="<?php echo $groups[$i]->name ?>" required>
                                                </div>
                                                <div class="input-form">
                                                    <div class="input_text">Иммунитет</div>
                                                    <input name="imm" placeholder="<?php echo $groups[$i]->immunity ?>" value="<?php echo $groups[$i]->immunity ?>" required>
                                                </div>
                                                <div class="col-3 float-left">
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="z" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][z]" id="<?php echo $groups[$i]->id ?>z" <?php if($sb->PregFlags('z', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>z">Флаг z - Полный доступ</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="a" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][a]" id="<?php echo $groups[$i]->id ?>a" <?php if($sb->PregFlags('a', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>a">Флаг а - Резервный слот</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="b" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][b]" id="<?php echo $groups[$i]->id ?>b" <?php if($sb->PregFlags('b', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>b">Флаг b - Рядовой флаг</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="c" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][c]" id="<?php echo $groups[$i]->id ?>c" <?php if($sb->PregFlags('c', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>c">Флаг c - Кик игроков</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="d" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][d]" id="<?php echo $groups[$i]->id ?>d" <?php if($sb->PregFlags('d', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>d">Флаг d - Бан игроков</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="e" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][e]" id="<?php echo $groups[$i]->id ?>e" <?php if($sb->PregFlags('e', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>e">Флаг e - Разбан игроков</label>
                                                    </div>
                                                </div>
                                                <div class="col-3 float-left">
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="f" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][f]" id="<?php echo $groups[$i]->id ?>f" <?php if($sb->PregFlags('f', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>f">Флаг f - Нанести вред игроку</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="g" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][g]" id="<?php echo $groups[$i]->id ?>g" <?php if($sb->PregFlags('g', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>g">Флаг g - Изменение карт</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="h" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][h]" id="<?php echo $groups[$i]->id ?>h" <?php if($sb->PregFlags('h', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>h">Флаг h - Изменение кваров</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="i" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][i]" id="<?php echo $groups[$i]->id ?>i" <?php if($sb->PregFlags('i', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>i">Флаг i - Выполнение конфигов</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="j" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][j]" id="<?php echo $groups[$i]->id ?>j" <?php if($sb->PregFlags('j', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>j">Флаг j - Привилегии в чате</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="k" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][k]" id="<?php echo $groups[$i]->id ?>k" <?php if($sb->PregFlags('k', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>k">Флаг k - Управление голосованиями</label>
                                                    </div>
                                                </div>
                                                <div class="col-3 float-left">
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="l" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][l]" id="<?php echo $groups[$i]->id ?>l" <?php if($sb->PregFlags('l', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>l">Флаг l - Установка пароля</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="m" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][m]" id="<?php echo $groups[$i]->id ?>m" <?php if($sb->PregFlags('m', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>m">Флаг m - Выполнение RCON команд</label>
                                                    </div>
                                                    <div class="input-form">
                                                        <input class="border-checkbox" value="n" type="checkbox" name="flags[<?php echo $groups[$i]->id ?>][n]" id="<?php echo $groups[$i]->id ?>n" <?php if($sb->PregFlags('n', $groups[$i]->flags)) echo 'checked'; ?>>
                                                        <label class="border-checkbox-label" for="<?php echo $groups[$i]->id ?>n">Флаг n - Изменение sv_cheats</label>
                                                    </div>
                                                </div>
                                                <div style="clear: both;">
                                                    <input type="submit" value="Изменить!" class="btn">
                                                </div>
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
            <div class="target" id="add1">
                <div class="card-header">
                    <h2>Добавление группы</h2>
                    <small>Тут вы можете добавить свою уникальную группу.. Ну да.</small>
                </div>
                <div class="card-container">
                    <form onsubmit='SendAjaxSB("#add_group", "add_group", "", "", ""); return false;' id="add_group" method="post">
                        <div class="input-form">
                            <div class="input_text">Имя</div>
                            <input name="name" required>
                        </div>
                            <div class="input-form">
                                <div class="input_text">Иммунитет</div>
                                <input name="imm" required>
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
                        <input type="submit" value="Создать!" class="btn">
                    </form>
                </div>
            </div>
        </div>
	</div>
</div>