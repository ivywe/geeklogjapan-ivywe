<?php

// Add device type to blocks table
$_SQL[] = "ALTER TABLE {$_TABLES['blocks']} ADD `device` VARCHAR( 15 ) NOT NULL DEFAULT 'all' AFTER `blockorder`";

// Add `language_items` table
$_SQL = "
CREATE TABLE {$_TABLES['language_items']} (
  id SERIAL NOT NULL,
  var_name VARCHAR(30) NOT NULL,
  language VARCHAR(30) NOT NULL,
  name VARCHAR(30) NOT NULL,
  value VARCHAR(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (id)
)
";

// Add `Language Admin` group
$_SQL[] = "INSERT INTO {$_TABLES['groups']} (grp_id, grp_name, grp_descr, grp_gl_core) VALUES ((SELECT nextval('{$_TABLES['groups']}_grp_id_seq')), 'Language Admin', 'Has full access to language', 1);";

// Add `language.edit` feature
$_SQL[] = "INSERT INTO {$_TABLES['features']} (ft_id, ft_name, ft_descr, ft_gl_core) VALUES ((SELECT nextval('{$_TABLES['features']}_ft_id_seq')), 'language.edit', 'Can manage Language Settings', 1)";

// Give `language.edit` feature to `Language Admin` group
$_SQL[] = "INSERT INTO {$_TABLES['access']} (acc_ft_id, acc_grp_id) VALUES (68,18) ";

// Add Root users to `Language Admin`
$_SQL[] = "INSERT INTO {$_TABLES['group_assignments']} (ug_main_grp_id, ug_uid, ug_grp_id) VALUES (18,NULL,1) ";

// Add `routes` table
$_SQL[] = "CREATE TABLE {$_TABLES['routes']} (
    rid SERIAL,
    method int NOT NULL DEFAULT 1,
    rule varchar(255) NOT NULL DEFAULT '',
    route varchar(255) NOT NULL DEFAULT '',
    priority int NOT NULL DEFAULT 100,
    PRIMARY KEY (rid)
)
";

// Add sample routes
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/article/@sid/print', '/article.php?story=@sid&mode=print', 100)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/article/@sid', '/article.php?story=@sid', 110)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/archives/@topic/@year/@month', '/directory.php?topic=@topic&year=@year&month=@month', 120)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/page/@page', '/staticpages/index.php?page=@page', 130)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/links/portal/@item', '/links/portal.php?what=link&item=@item', 140)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/links/category/@cat', '/links/index.php?category=@cat', 150)";
$_SQL[] = "INSERT INTO {$_TABLES['routes']} (method, rule, route, priority) VALUES (1, '/topic/@topic', '/index.php?topic=@topic', 160)";

// Change Topic Id (and Name) from 128 to 75 since we have an issue with the primary key on the topic_assignments table since it has to many bytes for tables with a utf8mb4 collation 
$_SQL[] = "ALTER TABLE {$_TABLES['topics']} CHANGE `tid` `tid` VARCHAR(75) NOT NULL default ''";
$_SQL[] = "ALTER TABLE {$_TABLES['topics']} CHANGE `topic` `topic` VARCHAR(75) NOT NULL";
$_SQL[] = "ALTER TABLE {$_TABLES['topic_assignments']} CHANGE `tid` `tid` VARCHAR(75) NOT NULL";
$_SQL[] = "ALTER TABLE {$_TABLES['sessions']} CHANGE `topic` `topic` VARCHAR(75) NOT NULL default ''";
$_SQL[] = "ALTER TABLE {$_TABLES['syndication']} CHANGE `topic` `topic` VARCHAR(75) NOT NULL default '::all'";
$_SQL[] = "ALTER TABLE {$_TABLES['syndication']} CHANGE `header_tid` `header_tid` VARCHAR(75) NOT NULL default 'none'";


/**
 * Add new config options
 *
 */
function update_ConfValuesFor212()
{
    global $_CONF, $_TABLES;

    require_once $_CONF['path_system'] . 'classes/config.class.php';

    $c = config::get_instance();

    $me = 'Core';

    // Add extra setting to hide_main_page_navigation
    $c->del('hide_main_page_navigation', $me);
    $c->add('hide_main_page_navigation','false','select',1,7,36,1310,TRUE, $me, 7);
    
    // New OAuth Service
    $c->add('github_login',0,'select',4,16,1,368,TRUE, $me, 16);
    $c->add('github_consumer_key','','text',4,16,NULL,369,TRUE, $me, 16);
    $c->add('github_consumer_secret','','text',4,16,NULL,370,TRUE, $me, 16);

    // New mobile cache
    $c->add('cache_templates',TRUE,'select',2,10,1,220,TRUE, $me, 10);      
    
    // New Block Autotag permissions
    $c->add('autotag_permissions_block', array(2, 2, 0, 0), '@select', 7, 41, 28, 1920, TRUE, $me, 37);    
    
    // New search config option
    $c->add('search_use_topic',FALSE,'select',0,6,1,677,TRUE, $me, 6);
    
    // New url routing option
    $c->add('url_routing',0,'select',0,0,37,1850,TRUE, $me, 0);

    // Add mail charset
    $c->add('mail_charset', '', 'text', 0, 1, NULL, 195, TRUE, $me, 1);
    
    // Delete MYSQL Dump Tab, section, and config options
    $c->del('tab_mysql', $me);
    $c->del('fs_mysql', $me);
    $c->del('allow_mysqldump', $me);
    $c->del('mysqldump_path', $me);
    $c->del('mysqldump_options', $me);
    $c->del('mysqldump_filename_mask', $me);
    
    // Add Database Backup config options
    $c->add('tab_database', NULL, 'tab', 0, 5, NULL, 0, TRUE, $me, 5);
    $c->add('fs_database_backup', NULL, 'fieldset', 0, 5, NULL, 0, TRUE, $me, 5);
    $c->add('dbdump_filename_prefix','geeklog_db_backup','text',0,5,NULL,170,TRUE, $me, 5);    
    $c->add('dbdump_tables_only',0,'select',0,5,0,175,TRUE, $me, 5);
    $c->add('dbdump_gzip',1,'select',0,5,0,180,TRUE, $me, 5); 
    $c->add('dbdump_max_files',10,'text',0,5,NULL,185,TRUE, $me, 5);

    // Add gravatar_identicon
    $c->add('gravatar_identicon','identicon','select',5,27,38,1620,FALSE, $me, 27);

    return true;
}
