<?php

Config::set('site_name', 'MyTest');

Config::set('languages', array('en', 'fr'));

// Routes. Route name => method prefix
Config::set('routes', array(
    'default' => '',
    'admin'   => 'admin_',
));

Config::set('default_route', 'default');
Config::set('default_language', 'en');
Config::set('default_controller', 'groups');
Config::set('default_action', 'index');

Config::set('db.host', 'localhost');
Config::set('db.user', 'yweekend_xsky');
Config::set('db.password', 'igor24');
Config::set('db.db_name', 'yweekend_test');

Config::set('salt', 'igor');