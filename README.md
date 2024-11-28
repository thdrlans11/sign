※ Laravel Default Setting Version 1.0

- ENV

    APP_NAME=DEFAULT #변경 필요

    APP_ENV=local #app/Providers/appServiceProvider.php 아이피걸어 별도 설정

    APP_KEY=base64:sypmAyavsGXL9cwlUileBrwAA9QYvkgTuEqkovfXIRc=

    APP_DEBUG=false #app/Providers/appServiceProvider.php 아이피걸어 별도 설정 

    APP_URL=http://node.m2comm.co.kr #변경 필요

    LOG_CHANNEL=daily

    LOG_DEPRECATIONS_CHANNEL=null

    LOG_LEVEL=debug

    DB_CONNECTION=mysql

    DB_HOST=127.0.0.1

    DB_PORT=3306

    DB_DATABASE=laravel #변경 필요

    DB_USERNAME=root #변경 필요

    DB_PASSWORD= #변경 필요

    #WISEU
    
    DB_HOST_WISEU=121.254.129.73

    DB_PORT_WISEU=1433

    DB_VERSION_WISEU=8.0

    DB_DATABASE_WISEU=wiseU

    DB_USERNAME_WISEU=wiseu

    DB_PASSWORD_WISEU=wiseu

    MASTER_PASSWORD = "master123!@#" #별도 변경

    ECARE_NUMBER = "" #별도 변경

- ENV

- QUERY LOG CREATE TABLE

    CREATE TABLE `query_logs` (
    `sid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `u_sid` bigint(20) unsigned DEFAULT NULL,
    `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '동작 설명',
    `query` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '쿼리',
    `content` text COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT '롤백 내용',
    `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'ip',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`sid`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='쿼리 로그';

- QUERY LOG CREATE TABLE

- QUERY BOARD CRATE TABLE

    CREATE TABLE `board_comments` (
    `code` varchar(255) NOT NULL,
    `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `bsid` int(11) unsigned NOT NULL DEFAULT 0,
    `csid` int(11) unsigned NOT NULL DEFAULT 0,
    `id` varchar(255) DEFAULT NULL,
    `name` varchar(255) NOT NULL,
    `comment` text DEFAULT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`sid`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

    CREATE TABLE `board_counters` (
    `sid` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `bsid` bigint(20) unsigned NOT NULL DEFAULT 0 COMMENT 'boards.sid',
    `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '접속 ip',
    `created_at` timestamp NULL DEFAULT NULL,
    `updated_at` timestamp NULL DEFAULT NULL,
    PRIMARY KEY (`sid`),
    KEY `board_counters_b_sid_index` (`bsid`)
    ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='게시판 조회수';

    CREATE TABLE `board_files` (
    `code` varchar(255) DEFAULT NULL,
    `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `bsid` int(11) unsigned DEFAULT NULL,
    `filename` varchar(255) DEFAULT NULL,
    `realfile` varchar(255) DEFAULT NULL,
    `download` int(11) unsigned DEFAULT 0,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`sid`),
    KEY `bsid` (`bsid`),
    FULLTEXT KEY `code` (`code`),
    FULLTEXT KEY `filename` (`filename`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

    CREATE TABLE `board_popups` (
    `code` varchar(255) DEFAULT NULL,
    `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `bsid` int(11) unsigned DEFAULT NULL,
    `skin` char(1) DEFAULT '1' COMMENT '팝업스킨',
    `popup_select` enum('1','2') NOT NULL DEFAULT '1' COMMENT '팝업 내용 선택:     P:bbs_popup.popup_content, N:bbs_tbl.content',
    `popup_content` longtext DEFAULT NULL COMMENT '팝업 내용',
    `width` int(4) unsigned DEFAULT NULL COMMENT '팝업창 가로길이',
    `height` int(4) unsigned DEFAULT NULL COMMENT '팝업창 세로길이',
    `position_x` int(4) unsigned DEFAULT NULL COMMENT '왼쪽 상단(0,0)으로부터 x축 위치',
    `position_y` int(4) unsigned DEFAULT NULL COMMENT '왼쪽 상단(0,0)으로부터 y축 위치',
    `popup_detail` enum('Y','N') DEFAULT 'N' COMMENT '팝업자세히보기 설정 여부',
    `popup_linkurl` varchar(255) DEFAULT NULL COMMENT '팝업자세히보기 링크URL',
    `startdate` date DEFAULT NULL COMMENT '팝업 표시 시작일시',
    `enddate` date DEFAULT NULL COMMENT '팝업 표시 종료일시',
    `created_at` varchar(255) DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    PRIMARY KEY (`sid`),
    KEY `bsid` (`bsid`),
    FULLTEXT KEY `code` (`code`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

    CREATE TABLE `boards` (
    `code` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `sid` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `fid` int(11) unsigned DEFAULT NULL,
    `category` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `id` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `name` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `email` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `subject` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `content` longtext CHARACTER SET euckr DEFAULT NULL,
    `linkurl` varchar(255) CHARACTER SET euckr DEFAULT NULL,
    `linkclick` int(11) NOT NULL DEFAULT 0,
    `date_type` enum('D','L') DEFAULT NULL,
    `sdate` datetime DEFAULT NULL,
    `edate` datetime DEFAULT NULL,
    `place` varchar(255) DEFAULT NULL,
    `sponsor` varchar(255) DEFAULT NULL,
    `fee` varchar(45) DEFAULT NULL,
    `inquiry` varchar(255) DEFAULT NULL,
    `addfile` varchar(255) DEFAULT NULL,
    `addreal` varchar(255) DEFAULT NULL,
    `ref` int(11) unsigned DEFAULT 0,
    `thread` varchar(255) CHARACTER SET euckr DEFAULT 'A',
    `comment` int(11) unsigned DEFAULT 0,
    `notice` enum('Y','N') CHARACTER SET euckr DEFAULT 'N',
    `popup` enum('Y','N') CHARACTER SET euckr DEFAULT 'N',
    `main` enum('Y','N') CHARACTER SET euckr DEFAULT 'Y',
    `hide` enum('Y','N') CHARACTER SET euckr DEFAULT 'Y',
    `ip` varchar(255) CHARACTER SET euckr NOT NULL,
    `created_at` datetime DEFAULT NULL,
    `updated_at` datetime DEFAULT NULL,
    `deleted_at` datetime DEFAULT NULL,
    PRIMARY KEY (`sid`),
    FULLTEXT KEY `code` (`code`),
    FULLTEXT KEY `category` (`category`),
    FULLTEXT KEY `id` (`id`),
    FULLTEXT KEY `name` (`name`),
    FULLTEXT KEY `email` (`email`),
    FULLTEXT KEY `subject` (`subject`)
    ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;


- QUERY BOARD CREATE TABLE