<?php !isset($_SESSION['user_admin']) && die(); if(!empty($Db->db_data['SourceBans']) && isset($_GET['c'])) header("Location: /sb/"); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <form onsubmit='SendAjaxSB("#install", "install", "", "", ""); return false;' id="install" method="post">
                <div class="card-header">
                    <ul class="tab-nav text-center fw-nav">
                        <li id="home" class="change active-ma"><a> Основная информация</a></li>
                        <li id="add" class="change"><a> Добавить сервер</a></li>
                        <li id="inst" class="change"><a> Инструкция</a></li>
                    </ul>
                </div>
                <div class="target" id="home1">
                    <div class="card-header text-center">
                        <h2>Подключение к БД</h2>
                        <small>Установка подключения к БД, и создание таблиц</small>
                    </div>
                    <div class="card-container">
                        <div class="input-form">
                            <div class="input_text">Хост / IP</div>
                            <input name="host" placeholder="192.0.0.1">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Порт</div>
                            <input name="ports" placeholder="3306" value="3306">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Пользователь</div>
                            <input name="user">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Название Базы Данных</div>
                            <input name="db_1">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Пароль от Базы Данных</div>
                            <input name="pass" type="password">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Префикс таблиц</div>
                            <input name="table" placeholder="sb_" value="sb_">
                        </div>
                        <div class="input-form">
                            <div class="input_text">Название мода в LR</div>
                            <input name="name" placeholder="SourceBans" value="SourceBans">
                        </div>
                        <div class="input-form">
                            <label>Игра</label>
                            <select name="mod">
                                <option value="730">CS:GO</option>
                                <option value="240">CS:S</option>
                            </select>
                        </div>
                        <div class="input-form">
                            <label>STEAM / NO - STEAM сервер</label>
                            <select name="nosteam">
                                <option value="1">STEAM</option>
                                <option value="0">NO-STEAM</option>
                            </select>
                        </div>
                        <input type="submit" value="Установить!" class="btn">
                    </div>
                </div>
                <div class="target" id="add1">
                    <div class="card-header text-center">
                        <h2>Добавление сервера</h2>
                        <small>Добавление сервера в БД</small>
                    </div>
                    <div class="card-container">
                        <div class="input-form">
                            <div class="input_text">IP</div>
                            <input name="ip" placeholder="192.0.0.1">
                        </div>
                        <div class="input-form">
                            <div class="input_text">PORT</div>
                            <input name="port" placeholder="27015">
                        </div>
                        <div class="input-form">
                            <div class="input_text">RCON</div>
                            <input name="rcon" type="password">
                        </div>
                        <input type="submit" value="Установить!" class="btn">
                    </div>
                </div>
            </form>
            <div class="target" id="inst1">
                <div class="card-header text-center">
                    <h2>Инструкция</h2>
                    <small>Инструкция для чайников, которые не знают что такое алфавит</small>
                </div>
                <div class="card-container">
                    <ol>
                        <li class="tn">
                            1) Настройка подключения к базе данных
                            <ol class="tn">
                                <li class="tn"><p>
                                    Зайдите в панель управления веб-хостингом и найдите вкладку данных MySql(см рис)
                                </p></li>
                                <li class="tn">
                                    <img class="max-install" src="app/modules/module_page_sb/temp/img/install2.jpg">
                                </li>
                                <li class="tn"><p>
                                    В поле host/ip  вставьте значение из пункта “host”
                                </p></li>
                                <li class="tn"><p>
                                    В поле “Пользователь” вставьте значение из пункта  “Логин”
                                </p></li>
                                <li class="tn"><p>
                                    В поле “Пароль базы данных” вставьте значение из пункта “Пароль”
                                </p></li>
                                <li class="tn"><p>
                                    В поле название базы данных вставьте название вашей бд с данными серера(см рис)
                                </p></li>
                                <li class="tn">
                                    <img class="max-install" src="app/modules/module_page_sb/temp/img/install1.jpg">
                                </li>
                                <li class="tn"><p>
                                    В пункт “Префикс таблиц ” вставьте префикс ваших таблиц sb(см рис- phpmyadmin)
                                </p></li>
                                <li class="tn">
                                    <img class="max-install" src="app/modules/module_page_sb/temp/img/install3.jpg">
                                </li>
                            </ol> 
                        </li>
                    </ol>
                    <ol>
                        <li class="tn">
                            2) Настройка сервера
                            <ol class="tn">
                                <li class="tn"><p>
                                    В пункт ip вставьте ip своего сервера(до двоеточия)
                                </p></li>
                                <li class="tn"><p>
                                    В пункт порт вставьте значения ip сервера после двоеточия
                                </p></li>
                                <li class="tn"><p>
                                    В пункт RCON вставьте rcon пароль сервера(переменная rcon_passowrd)
                                </p></li>
                                <li class="text-center"><strong>
                                    Все готово, жмите “Установить!”
                                </strong></li>
                            </ol>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>