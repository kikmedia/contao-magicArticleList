-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************

-- 
-- Table `tl_article`
-- 

CREATE TABLE `tl_article` (
  `exclude_article_list` char(1) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_module`
-- 

CREATE TABLE `tl_module` (
  `anchor_navigation` char(1) NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;