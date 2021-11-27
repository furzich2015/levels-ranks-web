
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Структура таблицы  `sb_admins`
--

CREATE TABLE `{prefix}admins` (
  `aid` int(6) NOT NULL,
  `user` varchar(64) NOT NULL,
  `authid` varchar(64) NOT NULL DEFAULT '',
  `password` varchar(128) NOT NULL,
  `gid` int(6) NOT NULL,
  `email` varchar(128) NOT NULL,
  `validate` varchar(128) DEFAULT NULL,
  `extraflags` int(10) NOT NULL,
  `immunity` int(10) NOT NULL DEFAULT '0',
  `srv_group` varchar(128) DEFAULT NULL,
  `srv_flags` varchar(64) DEFAULT NULL,
  `srv_password` varchar(128) DEFAULT NULL,
  `lastvisit` int(11) DEFAULT NULL,
  `expired` int(11) DEFAULT NULL,
  `skype` varchar(128) DEFAULT NULL,
  `comment` varchar(128) DEFAULT NULL,
  `vk` varchar(128) DEFAULT NULL,
  `support` int(6) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Структура таблицы  `sb_admins_servers_groups`
--

CREATE TABLE `{prefix}admins_servers_groups` (
  `admin_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL,
  `srv_group_id` int(10) NOT NULL,
  `server_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
--

CREATE TABLE `{prefix}bans` (
  `bid` int(6) NOT NULL,
  `ip` varchar(32) DEFAULT NULL,
  `authid` varchar(64) NOT NULL DEFAULT '',
  `name` varchar(128) NOT NULL DEFAULT 'unnamed',
  `created` int(11) NOT NULL DEFAULT '0',
  `ends` int(11) NOT NULL DEFAULT '0',
  `length` int(10) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `aid` int(6) NOT NULL DEFAULT '0',
  `adminIp` varchar(32) NOT NULL DEFAULT '',
  `sid` int(6) NOT NULL DEFAULT '0',
  `country` varchar(4) DEFAULT NULL,
  `RemovedBy` int(8) DEFAULT NULL,
  `RemoveType` varchar(3) DEFAULT NULL,
  `RemovedOn` int(10) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0',
  `ureason` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Структура таблицы  `sb_comms`
--

CREATE TABLE `{prefix}comms` (
  `bid` int(6) NOT NULL,
  `authid` varchar(64) NOT NULL,
  `name` varchar(128) NOT NULL DEFAULT 'unnamed',
  `created` int(11) NOT NULL DEFAULT '0',
  `ends` int(11) NOT NULL DEFAULT '0',
  `length` int(10) NOT NULL DEFAULT '0',
  `reason` text NOT NULL,
  `aid` int(6) NOT NULL DEFAULT '0',
  `adminIp` varchar(32) NOT NULL DEFAULT '',
  `sid` int(6) NOT NULL DEFAULT '0',
  `RemovedBy` int(8) DEFAULT NULL,
  `RemoveType` varchar(3) DEFAULT NULL,
  `RemovedOn` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '1 - Mute, 2 - Gag',
  `ureason` text
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы  `sb_groups`
--

CREATE TABLE `{prefix}groups` (
  `gid` int(6) NOT NULL,
  `type` smallint(6) NOT NULL DEFAULT '0',
  `name` varchar(128) NOT NULL DEFAULT 'unnamed',
  `flags` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Структура таблицы  `sb_overrides`
--

CREATE TABLE `{prefix}overrides` (
  `id` int(11) NOT NULL,
  `type` enum('command','group') NOT NULL,
  `name` varchar(32) NOT NULL,
  `flags` varchar(30) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы  `sb_protests`
--

CREATE TABLE `{prefix}protests` (
  `pid` int(6) NOT NULL,
  `bid` int(6) NOT NULL,
  `datesubmitted` int(11) NOT NULL,
  `reason` text NOT NULL,
  `email` varchar(128) NOT NULL,
  `archiv` tinyint(1) DEFAULT '0',
  `archivedby` int(11) DEFAULT NULL,
  `pip` varchar(64) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы  `sb_servers`
--

CREATE TABLE `{prefix}servers` (
  `sid` int(6) NOT NULL,
  `priority` int(11) NOT NULL,
  `ip` varchar(64) NOT NULL,
  `port` int(5) NOT NULL,
  `rcon` varchar(256) DEFAULT NULL,
  `modid` int(10) NOT NULL,
  `enabled` tinyint(4) NOT NULL DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Структура таблицы  `sb_servers_groups`
--

CREATE TABLE `{prefix}servers_groups` (
  `server_id` int(10) NOT NULL,
  `group_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- --------------------------------------------------------

--
-- Структура таблицы  `sb_srvgroups`
--

CREATE TABLE `{prefix}srvgroups` (
  `id` int(10) UNSIGNED NOT NULL,
  `flags` varchar(30) NOT NULL,
  `immunity` int(10) UNSIGNED NOT NULL,
  `name` varchar(120) NOT NULL,
  `groups_immune` varchar(255) NOT NULL,
  `maxbantime` int(11) NOT NULL DEFAULT '-1',
  `maxmutetime` int(11) NOT NULL DEFAULT '-1'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Структура таблицы  `sb_srvgroups_overrides`
--

CREATE TABLE `{prefix}srvgroups_overrides` (
  `id` int(11) NOT NULL,
  `group_id` smallint(5) UNSIGNED NOT NULL,
  `type` enum('command','group') NOT NULL,
  `name` varchar(32) NOT NULL,
  `access` enum('allow','deny') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы  `sb_submissions`
--

CREATE TABLE `{prefix}submissions` (
  `subid` int(6) NOT NULL,
  `submitted` int(11) NOT NULL,
  `ModID` int(6) NOT NULL,
  `SteamId` varchar(64) NOT NULL DEFAULT 'unnamed',
  `name` varchar(128) NOT NULL,
  `email` varchar(128) NOT NULL,
  `reason` text NOT NULL,
  `ip` varchar(64) NOT NULL,
  `subname` varchar(128) DEFAULT NULL,
  `sip` varchar(64) DEFAULT NULL,
  `archiv` tinyint(1) DEFAULT '0',
  `archivedby` int(11) DEFAULT NULL,
  `server` tinyint(3) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Структура таблицы  `sb_warns`
--

CREATE TABLE `{prefix}warns` (
  `id` int(11) NOT NULL,
  `arecipient` int(11) NOT NULL,
  `afrom` int(11) NOT NULL,
  `expires` int(11) NOT NULL,
  `reason` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `sb_admins`
--
ALTER TABLE `{prefix}admins`
  ADD PRIMARY KEY (`aid`),
  ADD UNIQUE KEY `user` (`user`);

--
-- Индексы таблицы `sb_bans`
--
ALTER TABLE `{prefix}bans`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `sid` (`sid`);
ALTER TABLE `{prefix}bans` ADD FULLTEXT KEY `reason` (`reason`);
ALTER TABLE `{prefix}bans` ADD FULLTEXT KEY `authid_2` (`authid`);

--
-- Индексы таблицы `sb_comms`
--
ALTER TABLE `{prefix}comms`
  ADD PRIMARY KEY (`bid`),
  ADD KEY `sid` (`sid`),
  ADD KEY `type` (`type`),
  ADD KEY `RemoveType` (`RemoveType`),
  ADD KEY `authid` (`authid`),
  ADD KEY `created` (`created`),
  ADD KEY `aid` (`aid`);

--
-- Индексы таблицы `sb_groups`
--
ALTER TABLE `{prefix}groups`
  ADD PRIMARY KEY (`gid`);

--
-- Индексы таблицы `sb_overrides`
--
ALTER TABLE `{prefix}overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `type` (`type`,`name`);

--
-- Индексы таблицы `sb_protests`
--
ALTER TABLE `{prefix}protests`
  ADD PRIMARY KEY (`pid`),
  ADD KEY `bid` (`bid`);

--
-- Индексы таблицы `sb_servers`
--
ALTER TABLE `{prefix}servers`
  ADD PRIMARY KEY (`sid`),
  ADD UNIQUE KEY `ip` (`ip`,`port`);

--
-- Индексы таблицы `sb_servers_groups`
--
ALTER TABLE `{prefix}servers_groups`
  ADD PRIMARY KEY (`server_id`,`group_id`);

--
-- Индексы таблицы `sb_srvgroups`
--
ALTER TABLE `{prefix}srvgroups`
  ADD PRIMARY KEY (`id`);

--
-- Индексы таблицы `sb_srvgroups_overrides`
--
ALTER TABLE `{prefix}srvgroups_overrides`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `group_id` (`group_id`,`type`,`name`);

--
-- Индексы таблицы `sb_submissions`
--
ALTER TABLE `{prefix}submissions`
  ADD PRIMARY KEY (`subid`);

--
-- Индексы таблицы `sb_warns`
--
ALTER TABLE `{prefix}warns`
  ADD UNIQUE KEY `id` (`id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `sb_admins`
--
ALTER TABLE `{prefix}admins`
  MODIFY `aid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `sb_bans`
--
ALTER TABLE `{prefix}bans`
  MODIFY `bid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT для таблицы `sb_comms`
--
ALTER TABLE `{prefix}comms`
  MODIFY `bid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT для таблицы `sb_groups`
--
ALTER TABLE `{prefix}groups`
  MODIFY `gid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `sb_overrides`
--
ALTER TABLE `{prefix}overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sb_protests`
--
ALTER TABLE `{prefix}protests`
  MODIFY `pid` int(6) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sb_servers`
--
ALTER TABLE `{prefix}servers`
  MODIFY `sid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT для таблицы `sb_srvgroups`
--
ALTER TABLE `{prefix}srvgroups`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT для таблицы `sb_srvgroups_overrides`
--
ALTER TABLE `{prefix}srvgroups_overrides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT для таблицы `sb_submissions`
--
ALTER TABLE `{prefix}submissions`
  MODIFY `subid` int(6) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблицы `sb_warns`
--
ALTER TABLE `{prefix}warns`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
