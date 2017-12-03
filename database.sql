-- --------------------------------------------------------
-- Host:                         localhost
-- Server version:               10.1.9-MariaDB-log - mariadb.org binary distribution
-- Server OS:                    Win32
-- HeidiSQL Version:             9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Dumping database structure for boompanel
CREATE DATABASE IF NOT EXISTS `boompanel` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;
USE `boompanel`;


-- Dumping structure for table boompanel.bp_admins
CREATE TABLE IF NOT EXISTS `bp_admins` (
	`aid` INT(11) NOT NULL AUTO_INCREMENT,
	`pid` INT(11) NOT NULL,
	`sid` INT(11) NOT NULL,
	`gid` INT(11) NOT NULL,
	`add_time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`aid`),
	INDEX `gid` (`gid`),
	INDEX `sid` (`sid`),
	INDEX `pid` (`pid`),
	CONSTRAINT `bp_admins_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `bp_admins_ibfk_2` FOREIGN KEY (`gid`) REFERENCES `bp_admin_groups` (`id`) ON UPDATE CASCADE,
	CONSTRAINT `bp_admins_ibfk_3` FOREIGN KEY (`sid`) REFERENCES `bp_servers` (`id`) ON UPDATE CASCADE
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=8
;



-- Dumping data for table boompanel.bp_admins: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_admins` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_admin_groups
CREATE TABLE IF NOT EXISTS `bp_admin_groups` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`name` VARCHAR(50) NOT NULL COLLATE 'utf8_general_ci',
	`flags` VARCHAR(25) NOT NULL COLLATE 'utf8_general_ci',
	`immunity` INT(3) NOT NULL DEFAULT '0',
	`usetime` INT(11) NOT NULL DEFAULT '0',
	`isadmingroup` INT(1) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`)
)
COLLATE='utf8mb4_unicode_ci'
ENGINE=InnoDB
AUTO_INCREMENT=11
;



-- Dumping data for table boompanel.bp_admin_groups: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_admin_groups` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_admin_groups` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_bans
CREATE TABLE IF NOT EXISTS `bp_bans` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `reason` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `length` int(11) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unbanned` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `admin_id` (`aid`),
  KEY `sid` (`sid`),
  KEY `pid` (`pid`),
  CONSTRAINT `bp_bans_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `bp_bans_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `bp_servers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `bp_bans_ibfk_3` FOREIGN KEY (`aid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_bans: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_bans` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_bans` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_chat
CREATE TABLE IF NOT EXISTS `bp_chat` (
  `lid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `message` varchar(256) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` int(1) NOT NULL DEFAULT '0',
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`lid`),
  KEY `player_id` (`pid`),
  KEY `server_id` (`sid`),
  CONSTRAINT `bp_chat_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `bp_chat_ibfk_2` FOREIGN KEY (`sid`) REFERENCES `bp_servers` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_chat: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_chat` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_chat` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_countries
CREATE TABLE IF NOT EXISTS `bp_countries` (
  `country_code` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  UNIQUE KEY `country_uniqe` (`country_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_countries: ~246 rows (approximately)
/*!40000 ALTER TABLE `bp_countries` DISABLE KEYS */;
INSERT IGNORE INTO `bp_countries` (`country_code`, `country_name`) VALUES
	('AD', 'Andorra'),
	('AE', 'United-Arab-Emirates'),
	('AF', 'Afghanistan'),
	('AG', 'Antigua-and-Barbuda'),
	('AI', 'Anguilla'),
	('AL', 'Albania'),
	('AM', 'Armenia'),
	('AN', 'Netherlands-Antilles'),
	('AO', 'Angola'),
	('AQ', 'Antarctica'),
	('AR', 'Argentina'),
	('AT', 'Austria'),
	('AU', 'Australia'),
	('AW', 'Aruba'),
	('AZ', 'Azerbaijan'),
	('BA', 'Bosnia-and-Herzegovina'),
	('BB', 'Barbados'),
	('BD', 'Bangladesh'),
	('BE', 'Belgium'),
	('BF', 'Burkina-Faso'),
	('BG', 'Bulgaria'),
	('BH', 'Bahrain'),
	('BI', 'Burundi'),
	('BY', 'Belarus'),
	('BJ', 'Benin'),
	('BM', 'Bermuda'),
	('BN', 'Brunei-Darussalam'),
	('BO', 'Bolivia'),
	('BR', 'Brazil'),
	('BS', 'Bahamas'),
	('BT', 'Bhutan'),
	('BV', 'Bouvet-Island'),
	('BW', 'Botswana'),
	('BZ', 'Belize'),
	('CA', 'Canada'),
	('CC', 'Cocos-(Keeling)-Islands'),
	('CF', 'Central-African-Republic'),
	('CG', 'Congo'),
	('CH', 'Switzerland'),
	('CI', 'Ivory-Coast'),
	('CY', 'Cyprus'),
	('CK', 'Cook-Islands'),
	('CL', 'Chile'),
	('CM', 'Cameroon'),
	('CN', 'China'),
	('CO', 'Colombia'),
	('CR', 'Costa-Rica'),
	('CU', 'Cuba'),
	('CV', 'Cape-Verde'),
	('CX', 'Christmas-Island'),
	('CZ', 'Czech-Republic'),
	('DE', 'Germany'),
	('DJ', 'Djibouti'),
	('DK', 'Denmark'),
	('DM', 'Dominica'),
	('DO', 'Dominican-Republic'),
	('DS', 'American-Samoa'),
	('DZ', 'Algeria'),
	('EC', 'Ecuador'),
	('EE', 'Estonia'),
	('EG', 'Egypt'),
	('EH', 'Western-Sahara'),
	('ER', 'Eritrea'),
	('ES', 'Spain'),
	('ET', 'Ethiopia'),
	('FI', 'Finland'),
	('FJ', 'Fiji'),
	('FK', 'Falkland-Islands-(Malvinas)'),
	('FM', 'Micronesia,-Federated-States-of'),
	('FO', 'Faroe-Islands'),
	('FR', 'France'),
	('FX', 'France,-Metropolitan'),
	('GA', 'Gabon'),
	('GB', 'United-Kingdom'),
	('GD', 'Grenada'),
	('GE', 'Georgia'),
	('GF', 'French-Guiana'),
	('GH', 'Ghana'),
	('GI', 'Gibraltar'),
	('GY', 'Guyana'),
	('GK', 'Guernsey'),
	('GL', 'Greenland'),
	('GM', 'Gambia'),
	('GN', 'Guinea'),
	('GP', 'Guadeloupe'),
	('GQ', 'Equatorial-Guinea'),
	('GR', 'Greece'),
	('GS', 'South-Georgia-South-Sandwich-Islands'),
	('GT', 'Guatemala'),
	('GU', 'Guam'),
	('GW', 'Guinea-Bissau'),
	('HK', 'Hong-Kong'),
	('HM', 'Heard-and-Mc-Donald-Islands'),
	('HN', 'Honduras'),
	('HR', 'Croatia-(Hrvatska)'),
	('HT', 'Haiti'),
	('HU', 'Hungary'),
	('HZ', 'Unknown'),
	('ID', 'Indonesia'),
	('IE', 'Ireland'),
	('IL', 'Israel'),
	('IM', 'Isle-of-Man'),
	('IN', 'India'),
	('IO', 'British-Indian-Ocean-Territory'),
	('IQ', 'Iraq'),
	('IR', 'Iran-(Islamic-Republic-of)'),
	('IS', 'Iceland'),
	('IT', 'Italy'),
	('YE', 'Yemen'),
	('JE', 'Jersey'),
	('JM', 'Jamaica'),
	('JO', 'Jordan'),
	('JP', 'Japan'),
	('KE', 'Kenya'),
	('KG', 'Kyrgyzstan'),
	('KH', 'Cambodia'),
	('KI', 'Kiribati'),
	('KY', 'Cayman-Islands'),
	('KM', 'Comoros'),
	('KN', 'Saint-Kitts-and-Nevis'),
	('KP', 'Korea,-Democratic-People\'s-Republic-of'),
	('KR', 'Korea,-Republic-of'),
	('KW', 'Kuwait'),
	('KZ', 'Kazakhstan'),
	('LA', 'Lao-People\'s-Democratic-Republic'),
	('LB', 'Lebanon'),
	('LC', 'Saint-Lucia'),
	('LI', 'Liechtenstein'),
	('LY', 'Libyan-Arab-Jamahiriya'),
	('LK', 'Sri-Lanka'),
	('LR', 'Liberia'),
	('LS', 'Lesotho'),
	('LT', 'Lithuania'),
	('LU', 'Luxembourg'),
	('LV', 'Latvia'),
	('MA', 'Morocco'),
	('MC', 'Monaco'),
	('MD', 'Moldova,-Republic-of'),
	('ME', 'Montenegro'),
	('MG', 'Madagascar'),
	('MH', 'Marshall-Islands'),
	('MY', 'Malaysia'),
	('MK', 'Macedonia'),
	('ML', 'Mali'),
	('MM', 'Myanmar'),
	('MN', 'Mongolia'),
	('MO', 'Macau'),
	('MP', 'Northern-Mariana-Islands'),
	('MQ', 'Martinique'),
	('MR', 'Mauritania'),
	('MS', 'Montserrat'),
	('MT', 'Malta'),
	('MU', 'Mauritius'),
	('MV', 'Maldives'),
	('MW', 'Malawi'),
	('MX', 'Mexico'),
	('MZ', 'Mozambique'),
	('NA', 'Namibia'),
	('NC', 'New-Caledonia'),
	('NE', 'Niger'),
	('NF', 'Norfolk-Island'),
	('NG', 'Nigeria'),
	('NI', 'Nicaragua'),
	('NL', 'Netherlands'),
	('NO', 'Norway'),
	('NP', 'Nepal'),
	('NR', 'Nauru'),
	('NU', 'Niue'),
	('NZ', 'New-Zealand'),
	('OM', 'Oman'),
	('PA', 'Panama'),
	('PE', 'Peru'),
	('PF', 'French-Polynesia'),
	('PG', 'Papua-New-Guinea'),
	('PH', 'Philippines'),
	('PY', 'Paraguay'),
	('PK', 'Pakistan'),
	('PL', 'Poland'),
	('PM', 'St.-Pierre-and-Miquelon'),
	('PN', 'Pitcairn'),
	('PR', 'Puerto-Rico'),
	('PS', 'Palestine'),
	('PT', 'Portugal'),
	('PW', 'Palau'),
	('QA', 'Qatar'),
	('RE', 'Reunion'),
	('RO', 'Romania'),
	('RS', 'Serbia'),
	('RU', 'Russian-Federation'),
	('RW', 'Rwanda'),
	('SA', 'Saudi-Arabia'),
	('SB', 'Solomon-Islands'),
	('SC', 'Seychelles'),
	('SD', 'Sudan'),
	('SE', 'Sweden'),
	('SG', 'Singapore'),
	('SH', 'St.-Helena'),
	('SI', 'Slovenia'),
	('SY', 'Syrian-Arab-Republic'),
	('SJ', 'Svalbard-and-Jan-Mayen-Islands'),
	('SK', 'Slovakia'),
	('SL', 'Sierra-Leone'),
	('SM', 'San-Marino'),
	('SN', 'Senegal'),
	('SO', 'Somalia'),
	('SR', 'Suriname'),
	('ST', 'Sao-Tome-and-Principe'),
	('SV', 'El-Salvador'),
	('SZ', 'Swaziland'),
	('TC', 'Turks-and-Caicos-Islands'),
	('TD', 'Chad'),
	('TF', 'French-Southern-Territories'),
	('TG', 'Togo'),
	('TH', 'Thailand'),
	('TY', 'Mayotte'),
	('TJ', 'Tajikistan'),
	('TK', 'Tokelau'),
	('TM', 'Turkmenistan'),
	('TN', 'Tunisia'),
	('TO', 'Tonga'),
	('TP', 'East-Timor'),
	('TR', 'Turkey'),
	('TT', 'Trinidad-and-Tobago'),
	('TV', 'Tuvalu'),
	('TW', 'Taiwan'),
	('TZ', 'Tanzania,-United-Republic-of'),
	('UA', 'Ukraine'),
	('UG', 'Uganda'),
	('UY', 'Uruguay'),
	('UM', 'United-States-minor-outlying-islands'),
	('US', 'United-States'),
	('UZ', 'Uzbekistan'),
	('VA', 'Vatican-City-State'),
	('VC', 'Saint-Vincent-and-the-Grenadines'),
	('VE', 'Venezuela'),
	('VG', 'Virgin-Islands-(British)'),
	('VI', 'Virgin-Islands-(U.S.)'),
	('VN', 'Vietnam'),
	('VU', 'Vanuatu'),
	('WF', 'Wallis-and-Futuna-Islands'),
	('WS', 'Samoa'),
	('XK', 'Kosovo'),
	('ZA', 'South-Africa'),
	('ZM', 'Zambia'),
	('ZR', 'Zaire'),
	('ZW', 'Zimbabwe');
/*!40000 ALTER TABLE `bp_countries` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_flags
CREATE TABLE IF NOT EXISTS `bp_flags` (
  `flag` varchar(1) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL,
  `importance` int(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_flags: ~21 rows (approximately)
/*!40000 ALTER TABLE `bp_flags` DISABLE KEYS */;
INSERT IGNORE INTO `bp_flags` (`flag`, `name`, `importance`) VALUES
	('a', 'Reservation', 1),
	('b', 'Generic', 1),
	('c', 'Kick', 1),
	('d', 'Ban', 1),
	('e', 'Unban', 1),
	('f', 'Slay', 1),
	('g', 'Changemap', 1),
	('h', 'Cvar', 1),
	('i', 'Config', 1),
	('j', 'Chat', 1),
	('k', 'Vote', 1),
	('l', 'Password', 1),
	('m', 'Rcon', 1),
	('n', 'Cheats', 1),
	('z', 'Root', 1),
	('o', 'Flag_custom', 1),
	('p', 'Flag_custom', 1),
	('q', 'Flag_custom', 1),
	('r', 'Flag_custom', 1),
	('s', 'Flag_custom', 1),
	('t', 'Flag_custom', 1);
/*!40000 ALTER TABLE `bp_flags` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_mutegag
CREATE TABLE IF NOT EXISTS `bp_mutegag` (
  `bid` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `aid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `mgtype` int(1) NOT NULL DEFAULT '0',
  `length` int(11) NOT NULL,
  `reason` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `unbanned` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`bid`),
  KEY `pid` (`pid`),
  KEY `aid` (`aid`),
  KEY `sid` (`sid`),
  CONSTRAINT `bp_mutegag_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bp_mutegag_ibfk_2` FOREIGN KEY (`aid`) REFERENCES `bp_players` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `bp_mutegag_ibfk_3` FOREIGN KEY (`sid`) REFERENCES `bp_servers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_mutegag: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_mutegag` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_mutegag` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_panel_admins
CREATE TABLE IF NOT EXISTS `bp_panel_admins` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_panel_admins: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_panel_admins` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_panel_admins` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_players
CREATE TABLE IF NOT EXISTS `bp_players` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `steamid` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `steamid` (`steamid`),
  KEY `country_id` (`country`),
  CONSTRAINT `bp_players_ibfk_1` FOREIGN KEY (`country`) REFERENCES `bp_countries` (`country_code`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=168 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_players: ~1 rows (approximately)
/*!40000 ALTER TABLE `bp_players` DISABLE KEYS */;
INSERT IGNORE INTO `bp_players` (`id`, `steamid`, `country`) VALUES
	(0, '0', 'HZ');
/*!40000 ALTER TABLE `bp_players` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_players_ip
CREATE TABLE IF NOT EXISTS `bp_players_ip` (
  `pid` int(11) NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connections` int(6) NOT NULL DEFAULT '1',
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_index` (`pid`,`ip`),
  CONSTRAINT `bp_players_ip_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_players_ip: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_players_ip` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_players_ip` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_players_online
CREATE TABLE IF NOT EXISTS `bp_players_online` (
  `pid` int(11) NOT NULL,
  `sid` int(11) NOT NULL,
  `connected` timestamp DEFAULT 0 NOT NULL,
  `disconnected` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  KEY `server_id` (`sid`),
  KEY `player_id` (`pid`),
  CONSTRAINT `bp_players_online_ibfk_1` FOREIGN KEY (`sid`) REFERENCES `bp_servers` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `bp_players_online_ibfk_2` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_players_online: ~0 rows (approximately)
/*!40000 ALTER TABLE `bp_players_online` DISABLE KEYS */;
/*!40000 ALTER TABLE `bp_players_online` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_players_username
CREATE TABLE IF NOT EXISTS `bp_players_username` (
  `pid` int(11) NOT NULL,
  `username` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connections` int(6) NOT NULL DEFAULT '1',
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `unique_index` (`username`,`pid`),
  KEY `player_id` (`pid`),
  CONSTRAINT `bp_players_username_ibfk_1` FOREIGN KEY (`pid`) REFERENCES `bp_players` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_players_username: ~1 rows (approximately)
/*!40000 ALTER TABLE `bp_players_username` DISABLE KEYS */;
INSERT IGNORE INTO `bp_players_username` (`pid`, `username`, `connections`, `last_used`) VALUES
	(0, 'Console', 0, '2017-10-28 14:58:41');
/*!40000 ALTER TABLE `bp_players_username` ENABLE KEYS */;


-- Dumping structure for table boompanel.bp_servers
CREATE TABLE IF NOT EXISTS `bp_servers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `port` int(6) NOT NULL,
  `rcon_pw` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `fullIP` (`ip`,`port`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table boompanel.bp_servers: ~1 rows (approximately)
/*!40000 ALTER TABLE `bp_servers` DISABLE KEYS */;
INSERT IGNORE INTO `bp_servers` (`id`, `name`, `ip`, `port`, `rcon_pw`) VALUES
	(0, 'all servers', '', 0, '');
/*!40000 ALTER TABLE `bp_servers` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

