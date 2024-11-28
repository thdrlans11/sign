※ Laravel Default Setting Version 1.0

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