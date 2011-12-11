-- 
-- Table `tl_search_and_replace`
-- 

CREATE TABLE `tl_search_and_replace` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `pages` blob NULL,
  `recursive` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 
-- Table `tl_search_and_replace_rules`
-- 

CREATE TABLE `tl_search_and_replace_rules` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `title` varchar(255) NOT NULL default '',
  `search_table` varchar(255) NOT NULL default 'tl_content',
  `search_table_fields` varchar(255) NOT NULL default '',
  `hasPattern` char(1) NOT NULL default '1',
  `pattern` mediumtext NULL,
  `replacement` mediumtext NULL,
  `isRegex` char(1) NOT NULL default '',
  `modIgnoreCase` char(1) NOT NULL default '',
  `modMultiLine` char(1) NOT NULL default '',
  `modDotAll` char(1) NOT NULL default '',
  `modUngreedy` char(1) NOT NULL default '',
  `modUTF8` char(1) NOT NULL default '',
  `isActive` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;