-- phpMyAdmin SQL Dump
-- version 3.5.3
-- http://www.phpmyadmin.net
--
-- 主机: localhost
-- 生成日期: 2012 年 11 月 21 日 11:38
-- 服务器版本: 5.1.44
-- PHP 版本: 5.3.1

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- 数据库: `qidimission`
--

-- --------------------------------------------------------

--
-- 表的结构 `mi_post`
--

CREATE TABLE IF NOT EXISTS `mi_post` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_data` datetime NOT NULL,
  `post_data_modified` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `post_fraction` int(11) DEFAULT '0',
  `post_content` text COLLATE utf8_bin,
  `post_static` varchar(20) COLLATE utf8_bin DEFAULT 'public',
  `post_sort` bigint(20) DEFAULT '0',
  `post_name` text COLLATE utf8_bin NOT NULL,
  `post_parent` bigint(20) DEFAULT '0',
  `ping_url` text COLLATE utf8_bin,
  `post_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  `post_meta_type` varchar(20) COLLATE utf8_bin DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`),
  KEY `id_2` (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=24 ;