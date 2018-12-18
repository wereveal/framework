<?php
$sql_date = date('Y-m-d H:i:s');
$a_u = [
    'home'             => '/',
    'manager'          => '/manager/',
    'man_login'        => '/manager/login/',
    'man_logout'       => '/manager/logout/',
    'man_tests'        => '/manager/tests/',
    'man_test_results' => '/manager/tests/results/',
    'library'          => '/manager/config/',
    'lib_alias'        => '/manager/config/alias/',
    'lib_ajax'         => '/manager/config/ajax/',
    'lib_blocks'       => '/manager/config/blocks/',
    'lib_cache'        => '/manager/config/cache/',
    'lib_constants'    => '/manager/config/constants/',
    'lib_content'      => '/manager/config/content',
    'lib_groups'       => '/manager/config/groups/',
    'lib_nav'          => '/manager/config/navigation/',
    'lib_navgroups'    => '/manager/config/navgroups/',
    'lib_pages'        => '/manager/config/pages/',
    'lib_peeps'        => '/manager/config/people/',
    'lib_routes'       => '/manager/config/routes/',
    'lib_sitemap'      => '/manager/config/sitemap/',
    'lib_tests'        => '/manager/config/tests/',
    'lib_test_results' => '/manager/config/tests/results/',
    'lib_twig'         => '/manager/config/twig/',
    'lib_urls'         => '/manager/config/urls/',
    'lib_login'        => '/manager/config/login/',
    'lib_logout'       => '/manager/config/logout/',
    'error'            => '/error/',
    'sitemap'          => '/sitemap/',
    'shared'           => '/shared/'
];

$a_constants = [
    'admin_dir_name' => [
        'const_name'      => 'ADMIN_DIR_NAME',
        'const_value'     => 'manager',
        'const_immutable' => 'true'
    ],
    'assets_dir_name' => [
        'const_name'      => 'ASSETS_DIR_NAME',
        'const_value'     => 'assets',
        'const_immutable' => 'true'
    ],
    'cache_ttl' => [
        'const_name'      => 'CACHE_TTL',
        'const_value'     => '604800', // 7 days
        'const_immutable' => 'true'
    ],
    'cache_type' => [
        'const_name'      => 'CACHE_TYPE',
        'const_value'     => 'PhpFiles',
        'const_immutable' => 'true'
    ],
    'content_vcs' => [
        'const_name'      => 'CONTENT_VCS',
        'const_value'     => 'true',
        'const_immutable' => 'true'
    ],
    'copyright_date' => [
        'const_name'      => 'COPYRIGHT_DATE',
        'const_value'     => '2018',
        'const_immutable' => 'true'
    ],
    'css_dir_name' => [
        'const_name'      => 'CSS_DIR_NAME',
        'const_value'     => 'css',
        'const_immutable' => 'true'
    ],
	'display_date_format' => [
        'const_name'      => 'DISPLAY_DATE_FORMAT',
        'const_value'     => 'D, M j, Y',
        'const_immutable' => 'true'
    ],
    'display_phone_format' => [
        'const_name'      => 'DISPLAY_PHONE_FORMAT',
        'const_value'     => 'XXX-XXX-XXXX',
        'const_immutable' => 'true'
    ],
    'email_domain' => [
        'const_name'      => 'EMAIL_DOMAIN',
        'const_value'     => 'revealitconsulting.com',
        'const_immutable' => 'true'
    ],
    'email_form_to' => [
        'const_name'      => 'EMAIL_FORM_TO',
        'const_value'     => 'bill@revealitconsulting.com',
        'const_immutable' => 'true'
    ],
    'error_email_address' => [
        'const_name'      => 'ERROR_EMAIL_ADDRESS',
        'const_value'     => 'webmaster@revealitconsulting.com',
        'const_immutable' => 'true'
    ],
    'files_dir_name' => [
        'const_name'      => 'FILES_DIR_NAME',
        'const_value'     => 'files',
        'const_immutable' => 'true'
    ],
    'fonts_dir_name' => [
        'const_name'      => 'FONTS_DIR_NAME',
        'const_value'     => 'fonts',
        'const_immutable' => 'true'
    ],
    'html_dir_name' => [
        'const_name'      => 'HTML_DIR_NAME',
        'const_value'     => 'html',
        'const_immutable' => 'true'
    ],
    'image_dir_name' => [
        'const_name'      => 'IMAGE_DIR_NAME',
        'const_value'     => 'images',
        'const_immutable' => 'true'
    ],
    'js_dir_name' => [
        'const_name'      => 'JS_DIR_NAME',
        'const_value'     => 'js',
        'const_immutable' => 'true'
    ],
    'page_template' => [
        'const_name'      => 'PAGE_TEMPLATE',
        'const_value'     => 'index.twig',
        'const_immutable' => 'true'
    ],
    'private_dir_name' => [
        'const_name'      => 'PRIVATE_DIR_NAME',
        'const_value'     => 'private',
        'const_immutable' => 'true'
    ],
    'rights_holder' => [
        'const_name'      => 'RIGHTS_HOLDER',
        'const_value'     => 'Reveal IT Consulting',
        'const_immutable' => 'true'
    ],
    'scss_dir_name' => [
        'const_name'      => 'SCSS_DIR_NAME',
        'const_value'     => 'scss',
        'const_immutable' => 'true'
    ],
    'session_idle_time' => [
        'const_name'      => 'SESSION_IDLE_TIME',
        'const_value'     => '1800',
        'const_immutable' => 'true'
    ],
    'tmp_dir_name' => [
        'const_name'      => 'TMP_DIR_NAME',
        'const_value'     => 'tmp',
        'const_immutable' => 'true'
    ],
    'use_cache' => [
        'const_name'      => 'USE_CACHE',
        'const_value'     => 'false',
        'const_immutable' => 'true'
    ],
    'vendor_dir_name' => [
        'const_name'      => 'VENDOR_DIR_NAME',
        'const_value'     => 'vendor',
        'const_immutable' => 'true'
    ],
    'developer_mode' => [
        'const_name'      => 'DEVELOPER_MODE',
        'const_value'     => 'true',
        'const_immutable' => 'true'
    ]
];

$a_groups = [
	'superadmin' => [
        'group_name'        => 'SuperAdmin',
        'group_description' => 'The group for super administrators',
        'group_auth_level'  => 10,
        'group_immutable'   => 'true'
    ],
    'admin' =>  [
        'group_name'        => 'Admins',
        'group_description' => 'Manages the advanced configuration of the site.',
        'group_auth_level'  => 9,
        'group_immutable'   => 'true'
    ],
	'manager' => [
        'group_name'        => 'Managers',
        'group_description' => 'Managers for the app.',
        'group_auth_level'  => 8,
        'group_immutable'   => 'true'
    ],
	'editor' => [
        'group_name'        => 'Editor',
        'group_description' => 'Content Editors',
        'group_auth_level'  => 5,
        'group_immutable'   => 'true'
    ],
	'registered' => [
        'group_name'        => 'Registered',
        'group_description' => 'Allows access to non-anonymous pages.',
        'group_auth_level'  => 1,
        'group_immutable'   => 'true'
    ],
	'anonymous' => [
        'group_name'        => 'Anonymous',
        'group_description' => 'Not logged in or possibly unregistered.',
        'group_auth_level'  => 0,
        'group_immutable'   => 'true'
    ]
];

$a_urls = [
	'home'             => ['url_host' => 'self', 'url_text' => $a_u['home'],             'url_scheme' => 'https', 'url_immutable' => 'true'],
    'error'            => ['url_host' => 'self', 'url_text' => $a_u['error'],            'url_scheme' => 'https', 'url_immutable' => 'true'],
    'shared'           => ['url_host' => 'self', 'url_text' => $a_u['shared'],           'url_scheme' => 'ritc',  'url_immutable' => 'true'],
    'sitemap'          => ['url_host' => 'self', 'url_text' => $a_u['sitemap'],          'url_scheme' => 'https', 'url_immutable' => 'true'],
	'manager'          => ['url_host' => 'self', 'url_text' => $a_u['manager'],          'url_scheme' => 'https', 'url_immutable' => 'true'],
	'man_login'        => ['url_host' => 'self', 'url_text' => $a_u['man_login'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
	'man_logout'       => ['url_host' => 'self', 'url_text' => $a_u['man_logout'],       'url_scheme' => 'https', 'url_immutable' => 'true'],
    'man_tests'        => ['url_host' => 'self', 'url_text' => $a_u['man_tests'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
    'man_test_results' => ['url_host' => 'self', 'url_text' => $a_u['man_test_results'], 'url_scheme' => 'https', 'url_immutable' => 'true'],
	'library'          => ['url_host' => 'self', 'url_text' => $a_u['library'],          'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_alias'        => ['url_host' => 'self', 'url_text' => $a_u['lib_alias'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_ajax'         => ['url_host' => 'self', 'url_text' => $a_u['lib_ajax'],         'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_blocks'       => ['url_host' => 'self', 'url_text' => $a_u['lib_blocks'],       'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_cache'        => ['url_host' => 'self', 'url_text' => $a_u['lib_cache'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
	'lib_constants'    => ['url_host' => 'self', 'url_text' => $a_u['lib_constants'],    'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_content'      => ['url_host' => 'self', 'url_text' => $a_u['lib_content'],      'url_scheme' => 'https', 'url_immutable' => 'true'],
	'lib_groups'       => ['url_host' => 'self', 'url_text' => $a_u['lib_groups'],       'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_nav'          => ['url_host' => 'self', 'url_text' => $a_u['lib_nav'],          'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_navgroups'    => ['url_host' => 'self', 'url_text' => $a_u['lib_navgroups'],    'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_pages'        => ['url_host' => 'self', 'url_text' => $a_u['lib_pages'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
	'lib_peeps'        => ['url_host' => 'self', 'url_text' => $a_u['lib_peeps'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_routes'       => ['url_host' => 'self', 'url_text' => $a_u['lib_routes'],       'url_scheme' => 'https', 'url_immutable' => 'true'],
	'lib_sitemap'      => ['url_host' => 'self', 'url_text' => $a_u['lib_sitemap'],      'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_tests'        => ['url_host' => 'self', 'url_text' => $a_u['lib_tests'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_test_results' => ['url_host' => 'self', 'url_text' => $a_u['lib_test_results'], 'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_twig'         => ['url_host' => 'self', 'url_text' => $a_u['lib_twig'],         'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_urls'         => ['url_host' => 'self', 'url_text' => $a_u['lib_urls'],         'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_login'        => ['url_host' => 'self', 'url_text' => $a_u['lib_login'],        'url_scheme' => 'https', 'url_immutable' => 'true'],
    'lib_logout'       => ['url_host' => 'self', 'url_text' => $a_u['lib_logout'],       'url_scheme' => 'https', 'url_immutable' => 'true']
];

$a_people = [
	'superadmin' => [
        'login_id'        => 'SuperAdmin',
        'real_name'       => 'Super Admin',
        'short_name'      => 'GSA',
        'password'        => 'letGSAin',
        'description'     => 'The all powerful Admin',
        'is_logged_in'    => 'false',
        'last_logged_in'  => '1001-01-01',
        'bad_login_count' => 0,
        'bad_login_ts'    => 0,
        'is_active'       => 'true',
        'is_immutable'    => 'true',
        'created_on'      => $sql_date
	],
	'admin' => [
        'login_id'        => 'Admin',
        'real_name'       => 'Admin',
        'short_name'      => 'ADM',
        'password'        => 'letADMin',
        'description'     => 'Allowed to admin to site',
        'is_logged_in'    => 'false',
        'last_logged_in'  => '1001-01-01',
        'bad_login_count' => 0,
        'bad_login_ts'    => 0,
        'is_active'       => 'true',
        'is_immutable'    => 'true',
        'created_on'      => $sql_date
	],
	'manager' => [
        'login_id'        => 'Manager',
        'real_name'       => 'Manager',
        'short_name'      => 'MAN',
        'password'        => 'letMANin',
        'description'     => 'Allowed to manage non-critical aspects of site',
        'is_logged_in'    => 'false',
        'last_logged_in'  => '1001-01-01',
        'bad_login_count' => 0,
        'bad_login_ts'    => 0,
        'is_active'       => 'true',
        'is_immutable'    => 'true',
        'created_on'      => $sql_date
    ]
];

$a_navgroups = [
	'main' => [
        'ng_name'      => 'Main',
        'ng_active'    => 'true',
        'ng_default'   => 'true',
        'ng_immutable' => 'true'
    ],
    'config' => [
        'ng_name'      => 'Config',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'configlinks' => [
        'ng_name'      => 'ConfigLinks',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'configtestlinks' => [
        'ng_name'      => 'ConfigTestLinks',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'editor' => [
        'ng_name'      => 'Editor',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'editorlinks' => [
        'ng_name'      => 'EditorLinks',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'manager' => [
        'ng_name'      => 'Manager',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'managerlinks' => [
        'ng_name'      => 'ManagerLinks',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'pagelinks' => [
        'ng_name'      => 'PageLinks',
        'ng_active'    => 'false',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'registered' => [
        'ng_name'      => 'Registered',
        'ng_active'    => 'false',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
    'registeredlinks' => [
        'ng_name'      => 'RegisteredLinks',
        'ng_active'    => 'false',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ],
	'sitemap' => [
        'ng_name'      => 'Sitemap',
        'ng_active'    => 'true',
        'ng_default'   => 'false',
        'ng_immutable' => 'true'
    ]
];

$a_people_group = [
    [
        'people_id' => 'superadmin',
        'group_id'  => 'superadmin'
    ],
    [
        'people_id' => 'admin',
        'group_id'  => 'admin'
    ],
    [
        'people_id' => 'manager',
        'group_id'  => 'manager'
    ],
];

$a_routes = [
    'home' => [
        'url_id'          => 'home',
        'route_class'     => 'HomeController',
        'route_method'    => 'route',
        'route_action'    => '',
        'route_immutable' => 'true'
    ],
    'error' => [
        'url_id'          => 'error',
        'route_class'     => 'HomeController',
        'route_method'    => 'routeError',
        'route_action'    => '',
        'route_immutable' => 'true'
    ],
    'sitemap' => [
        'url_id'          => 'sitemap',
        'route_class'     => 'SitemapController',
        'route_method'    => 'route',
        'route_action'    => '',
        'route_immutable' => 'true'
    ],
	'manager' => [
	    'url_id'          => 'manager',
        'route_class'     => 'ManagerController',
        'route_method'    => 'route',
        'route_action'    => '',
        'route_immutable' => 'true'
	],
	'man_login' => [
	    'url_id'          => 'man_login',
        'route_class'     => 'ManagerController',
        'route_method'    => 'route',
        'route_action'    => 'login',
        'route_immutable' => 'true'
	],
	'man_logout' => [
	    'url_id'          => 'man_logout',
        'route_class'     => 'ManagerController',
        'route_method'    => 'route',
        'route_action'    => 'logout',
        'route_immutable' => 'true'
	],
    'man_tests' => [
        'url_id'          => 'man_tests',
        'route_class'     => 'ManagerController',
        'route_method'    => 'route',
        'route_action'    => 'tests',
        'route_immutable' => 'true'
    ],
    'man_test_results' => [
        'url_id'          => 'man_test_results',
        'route_class'     => 'ManagerController',
        'route_method'    => 'route',
        'route_action'    => 'tests',
        'route_immutable' => 'true'
    ],
	'library' => [
	    'url_id'          => 'library',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => '',
        'route_immutable' => 'true'
	],
    'lib_alias' => [
        'url_id'          => 'lib_alias',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'alias',
        'route_immutable' => 'true'
    ],
    'lib_ajax' => [
        'url_id'          => 'lib_ajax',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'ajax',
        'route_immutable' => 'true'
    ],
    'lib_blocks' => [
        'url_id'          => 'lib_blocks',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'blocks',
        'route_immutable' => 'true'
    ],
    'lib_cache' => [
        'url_id'          => 'lib_cache',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'cache',
        'route_immutable' => 'true'
    ],
	'lib_constants' => [
	    'url_id'          => 'lib_constants',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'constants',
        'route_immutable' => 'true'
	],
    'lib_content' => [
        'url_id'          => 'lib_content',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'content',
        'route_immutable' => 'true'
    ],
	'lib_groups' => [
	    'url_id'          => 'lib_groups',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'groups',
        'route_immutable' => 'true'
	],
	'lib_nav' => [
	    'url_id'          => 'lib_nav',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'navigation',
        'route_immutable' => 'true'
	],
    'lib_navgroups' => [
        'url_id'          => 'lib_navgroups',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'navgroups',
        'route_immutable' => 'true'
    ],
    'lib_pages' => [
        'url_id'          => 'lib_pages',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'pages',
        'route_immutable' => 'true'
    ],
	'lib_peeps' => [
	    'url_id'          => 'lib_peeps',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'people',
        'route_immutable' => 'true'
	],
    'lib_routes' => [
        'url_id'          => 'lib_routes',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'routes',
        'route_immutable' => 'true'
    ],
    'lib_sitemap' => [
        'url_id'          => 'lib_sitemap',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'sitemap',
        'route_immutable' => 'true'
    ],
    'lib_tests' => [
        'url_id'          => 'lib_tests',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'tests',
        'route_immutable' => 'true'
    ],
    'lib_test_results' => [
        'url_id'          => 'lib_test_results',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'tests',
        'route_immutable' => 'true'
    ],
    'lib_twig' => [
        'url_id'          => 'lib_twig',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'twig',
        'route_immutable' => 'true'
    ],
	'lib_urls' => [
	    'url_id'          => 'lib_urls',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'urls',
        'route_immutable' => 'true'
	],
    'lib_login' => [
        'url_id'          => 'lib_login',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'login',
        'route_immutable' => 'true'
    ],
    'lib_logout' => [
        'url_id'          => 'lib_logout',
        'route_class'     => 'LibraryController',
        'route_method'    => 'route',
        'route_action'    => 'logout',
        'route_immutable' => 'true'
    ]
];

$a_route_group_map = [
    ['route_id' => 'home',             'group_id' => 'anonymous'],
    ['route_id' => 'error',            'group_id' => 'anonymous'],
    ['route_id' => 'sitemap',          'group_id' => 'anonymous'],
	['route_id' => 'manager',          'group_id' => 'manager'],
	['route_id' => 'man_login',        'group_id' => 'anonymous'],
	['route_id' => 'man_logout',       'group_id' => 'manager'],
    ['route_id' => 'man_tests',        'group_id' => 'manager'],
    ['route_id' => 'man_test_results', 'group_id' => 'manager'],
	['route_id' => 'library',          'group_id' => 'admin'],
    ['route_id' => 'lib_alias',        'group_id' => 'admin'],
    ['route_id' => 'lib_ajax',         'group_id' => 'admin'],
    ['route_id' => 'lib_blocks',       'group_id' => 'admin'],
    ['route_id' => 'lib_cache',        'group_id' => 'admin'],
    ['route_id' => 'lib_constants',    'group_id' => 'admin'],
    ['route_id' => 'lib_content',      'group_id' => 'admin'],
	['route_id' => 'lib_groups',       'group_id' => 'admin'],
    ['route_id' => 'lib_nav',          'group_id' => 'admin'],
    ['route_id' => 'lib_navgroups',    'group_id' => 'admin'],
    ['route_id' => 'lib_pages',        'group_id' => 'admin'],
	['route_id' => 'lib_peeps',        'group_id' => 'admin'],
    ['route_id' => 'lib_routes',       'group_id' => 'admin'],
    ['route_id' => 'lib_sitemap',      'group_id' => 'admin'],
    ['route_id' => 'lib_twig',         'group_id' => 'admin'],
    ['route_id' => 'lib_urls',         'group_id' => 'admin'],
    ['route_id' => 'lib_tests',        'group_id' => 'admin'],
    ['route_id' => 'lib_test_results', 'group_id' => 'admin'],
    ['route_id' => 'lib_login',        'group_id' => 'anonymous'],
    ['route_id' => 'lib_logout',       'group_id' => 'admin']
];

$a_navigation = [
    'home' => [
        'url_id'          => 'home',
        'parent_id'       => 'home',
        'nav_name'        => 'home',
        'nav_text'        => 'Home',
        'nav_description' => 'Home page.',
        'nav_css'         => '',
        'nav_level'       => 1,
        'nav_order'       => 1,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'sitemap' => [
        'url_id'          => 'sitemap',
        'parent_id'       => 'sitemap',
        'nav_name'        => 'sitemap',
        'nav_text'        => 'Sitemap',
        'nav_description' => 'Sitemap.',
        'nav_css'         => '',
        'nav_level'       => 1,
        'nav_order'       => 2,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'library' => [
        'url_id'          => 'library',
        'parent_id'       => 'library',
        'nav_name'        => 'library',
        'nav_text'        => 'Advanced Config',
        'nav_description' => 'Backend Manager Page',
        'nav_css'         => '',
        'nav_level'       => 1,
        'nav_order'       => 4,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_alias'  => [
        'url_id'          => 'lib_alias',
        'parent_id'       => 'library',
        'nav_name'        => 'alias',
        'nav_text'        => 'URL Aliases',
        'nav_description' => 'Manage alias used throughout app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 1,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_blocks'  => [
        'url_id'          => 'lib_blocks',
        'parent_id'       => 'library',
        'nav_name'        => 'blocks',
        'nav_text'        => 'Blocks',
        'nav_description' => 'Manage blocks used throughout app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 2,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_cache'  => [
        'url_id'          => 'lib_cache',
        'parent_id'       => 'library',
        'nav_name'        => 'cache',
        'nav_text'        => 'Cache',
        'nav_description' => 'Manage cache used throughout app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 3,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_constants'  => [
        'url_id'          => 'lib_constants',
        'parent_id'       => 'library',
        'nav_name'        => 'constants',
        'nav_text'        => 'Constants',
        'nav_description' => 'Define constants used throughout app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 4,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_content' => [
        'url_id'          => 'lib_content',
        'parent_id'       => 'library',
        'nav_name'        => 'content',
        'nav_text'        => 'Content Manager',
        'nav_description' => 'Content Manager',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 5,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_groups' => [
        'url_id'          => 'lib_groups',
        'parent_id'       => 'library',
        'nav_name'        => 'groups',
        'nav_text'        => 'Groups',
        'nav_description' => 'Define Groups used for accessing app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 6,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_login' => [
        'url_id'          => 'lib_login',
        'parent_id'       => 'library',
        'nav_name'        => 'lib_login',
        'nav_text'        => 'Config Login',
        'nav_description' => 'Config Login',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 17,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'lib_logout' => [
        'url_id'          => 'lib_logout',
        'parent_id'       => 'library',
        'nav_name'        => 'lib_logout',
        'nav_text'        => 'Config Logout',
        'nav_description' => 'Config Logout',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 16,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_nav' => [
        'url_id'          => 'lib_nav',
        'parent_id'       => 'library',
        'nav_name'        => 'navigation',
        'nav_text'        => 'Navigation',
        'nav_description' => 'Define Navigation Links',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 7,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_navgroups' => [
        'url_id'          => 'lib_navgroups',
        'parent_id'       => 'library',
        'nav_name'        => 'navgroups',
        'nav_text'        => 'Navgroups',
        'nav_description' => 'Define Navigation Groups',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 8,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_pages' => [
        'url_id'          => 'lib_pages',
        'parent_id'       => 'library',
        'nav_name'        => 'pages',
        'nav_text'        => 'Pages',
        'nav_description' => 'Define Page values.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 9,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_peeps' => [
        'url_id'          => 'lib_peeps',
        'parent_id'       => 'library',
        'nav_name'        => 'people',
        'nav_text'        => 'People',
        'nav_description' => 'Setup people allowed to access app.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 10,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_routes' => [
        'url_id'          => 'lib_routes',
        'parent_id'       => 'library',
        'nav_name'        => 'routes',
        'nav_text'        => 'Routes',
        'nav_description' => 'Define routes used for where to go.',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 11,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_sitemap' => [
        'url_id'          => 'lib_sitemap',
        'parent_id'       => 'library',
        'nav_name'        => 'lib_sitemap',
        'nav_text'        => 'Sitemap Manager',
        'nav_description' => 'Sitemap Manager',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 12,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_tests' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'library',
        'nav_name'        => 'lib_tests',
        'nav_text'        => 'Configuration Tests',
        'nav_description' => 'Run Configuration Tests',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 15,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'lib_twig' => [
        'url_id'          => 'lib_twig',
        'parent_id'       => 'library',
        'nav_name'        => 'twig',
        'nav_text'        => 'Twig',
        'nav_description' => 'Define Twig prefix, directories, and templates',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 13,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'lib_urls' => [
        'url_id'          => 'lib_urls',
        'parent_id'       => 'library',
        'nav_name'        => 'urls',
        'nav_text'        => 'Urls',
        'nav_description' => 'Define the URLs used in the app',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 14,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'manager' => [
        'url_id'          => 'manager',
        'parent_id'       => 'manager',
        'nav_name'        => 'manager',
        'nav_text'        => 'Manager',
        'nav_description' => 'Manager Page',
        'nav_css'         => '',
        'nav_level'       => 1,
        'nav_order'       => 3,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'man_tests' => [
        'url_id'          => 'man_tests',
        'parent_id'       => 'manager',
        'nav_name'        => 'manager_tests',
        'nav_text'        => 'Manager Tests',
        'nav_description' => 'Manager Tests',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 1,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'man_login' => [
        'url_id'          => 'man_login',
        'parent_id'       => 'manager',
        'nav_name'        => 'manager_login',
        'nav_text'        => 'Manager Login',
        'nav_description' => 'Manager Login',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 3,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'man_logout' => [
        'url_id'          => 'man_logout',
        'parent_id'       => 'manager',
        'nav_name'        => 'manager_logout',
        'nav_text'        => 'Manager Logout',
        'nav_description' => 'Manager Logout',
        'nav_css'         => '',
        'nav_level'       => 2,
        'nav_order'       => 2,
        'nav_active'      => 'true',
        'nav_immutable'   => 'true'
    ],
    'constantsmodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'constantsmodel_test',
        'nav_text'        => 'ConstantsModelTest',
        'nav_description' => 'Constants Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'pagemodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'pagemodel_test',
        'nav_text'        => 'PageModelTest',
        'nav_description' => 'Page Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'peoplemodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'peoplemodel_test',
        'nav_text'        => 'PeopleModelTest',
        'nav_description' => 'People Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'urlsmodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'urlsmodel_test',
        'nav_text'        => 'UrlsModelTest',
        'nav_description' => 'Urls Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'navgroupsmodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'navgroupsmodel_test',
        'nav_text'        => 'NavgroupsModelTest',
        'nav_description' => 'Navgroups Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'navigationmodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'navigationmodel_test',
        'nav_text'        => 'NavigationModelTest',
        'nav_description' => 'Navigation Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ],
    'navngmapmodel_test' => [
        'url_id'          => 'lib_tests',
        'parent_id'       => 'lib_tests',
        'nav_name'        => 'navngmapmodel_test',
        'nav_text'        => 'NavNgMapModelTest',
        'nav_description' => 'NavNgMap Model Test',
        'nav_css'         => '',
        'nav_level'       => 3,
        'nav_order'       => 1,
        'nav_active'      => 'false',
        'nav_immutable'   => 'true'
    ]
];

$a_nav_ng_map = [
    ['ng_id' => 'config',       'nav_id' => 'home'],
    ['ng_id' => 'config',       'nav_id' => 'manager'],
    ['ng_id' => 'config',       'nav_id' => 'library'],
    ['ng_id' => 'config',       'nav_id' => 'lib_alias'],
    ['ng_id' => 'config',       'nav_id' => 'lib_blocks'],
    ['ng_id' => 'config',       'nav_id' => 'lib_cache'],
    ['ng_id' => 'config',       'nav_id' => 'lib_constants'],
    ['ng_id' => 'config',       'nav_id' => 'lib_content'],
    ['ng_id' => 'config',       'nav_id' => 'lib_groups'],
    ['ng_id' => 'config',       'nav_id' => 'lib_nav'],
    ['ng_id' => 'config',       'nav_id' => 'lib_pages'],
    ['ng_id' => 'config',       'nav_id' => 'lib_peeps'],
    ['ng_id' => 'config',       'nav_id' => 'lib_routes'],
    ['ng_id' => 'config',       'nav_id' => 'lib_sitemap'],
    ['ng_id' => 'config',       'nav_id' => 'lib_tests'],
    ['ng_id' => 'config',       'nav_id' => 'lib_twig'],
    ['ng_id' => 'config',       'nav_id' => 'lib_urls'],
    ['ng_id' => 'config',       'nav_id' => 'lib_login'],
    ['ng_id' => 'config',       'nav_id' => 'lib_logout'],
    ['ng_id' => 'configlinks',  'nav_id' => 'home'],
    ['ng_id' => 'configlinks',  'nav_id' => 'manager'],
    ['ng_id' => 'configlinks',  'nav_id' => 'library'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_alias'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_blocks'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_cache'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_constants'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_content'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_groups'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_nav'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_pages'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_peeps'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_routes'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_sitemap'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_tests'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_twig'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_urls'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_login'],
    ['ng_id' => 'configlinks',  'nav_id' => 'lib_logout'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'constantsmodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'pagemodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'peoplemodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'urlsmodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'navgroupsmodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'navigationmodel_test'],
    ['ng_id' => 'configtestlinks', 'nav_id' => 'navngmapmodel_test'],
    ['ng_id' => 'main',         'nav_id' => 'home'],
    ['ng_id' => 'manager',      'nav_id' => 'home'],
    ['ng_id' => 'manager',      'nav_id' => 'manager'],
    ['ng_id' => 'manager',      'nav_id' => 'library'],
    ['ng_id' => 'manager',      'nav_id' => 'man_tests'],
    ['ng_id' => 'manager',      'nav_id' => 'man_login'],
    ['ng_id' => 'manager',      'nav_id' => 'man_logout'],
    ['ng_id' => 'managerlinks', 'nav_id' => 'home'],
    ['ng_id' => 'managerlinks', 'nav_id' => 'manager'],
    ['ng_id' => 'managerlinks', 'nav_id' => 'man_tests'],
    ['ng_id' => 'managerlinks', 'nav_id' => 'man_login'],
    ['ng_id' => 'managerlinks', 'nav_id' => 'man_logout'],
    ['ng_id' => 'sitemap',      'nav_id' => 'home'],
    ['ng_id' => 'sitemap',      'nav_id' => 'sitemap']
];

$a_page = [
    'home' => [
        'url_id'           => 'home',
        'ng_id'            => 'main',
        'tpl_id'           => 'index',
        'page_type'        => 'text/html',
        'page_title'       => 'Home Page',
        'page_description' => 'Home Page',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'shared' => [
        'url_id'           => 'shared',
        'ng_id'            => 'main',
        'tpl_id'           => 'lib_shared',
        'page_type'        => 'text/html',
        'page_title'       => 'shared',
        'page_description' => 'shared content on multiple pages, do not change page title!',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'sitemap' => [
        'url_id'           => 'sitemap',
        'ng_id'            => 'main',
        'tpl_id'           => 'sitemap',
        'page_type'        => 'text/html',
        'page_title'       => 'Sitemap',
        'page_description' => 'Sitemap',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'error' => [
        'url_id'           => 'error',
        'ng_id'            => 'main',
        'tpl_id'           => 'error',
        'page_type'        => 'text/html',
        'page_title'       => 'Error Page',
        'page_description' => 'Error Page',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'manager' => [
        'url_id'           => 'manager',
        'ng_id'            => 'manager',
        'tpl_id'           => 'manager',
        'page_type'        => 'text/html',
        'page_title'       => 'Manager',
        'page_description' => 'Manage Web Site',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'manager_tests' => [
        'url_id'           => 'man_tests',
        'ng_id'            => 'manager',
        'tpl_id'           => 'test',
        'page_type'        => 'text/html',
        'page_title'       => 'Manager Tests',
        'page_description' => 'Manager Test',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'manager_man_test_results' => [
        'url_id'           => 'man_test_results',
        'ng_id'            => 'manager',
        'tpl_id'           => 'man_test_results',
        'page_type'        => 'text/html',
        'page_title'       => 'Manager Test Results',
        'page_description' => 'Manager Test Results',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'man_login' => [
        'url_id'           => 'man_login',
        'ng_id'            => 'manager',
        'tpl_id'           => 'man_login',
        'page_type'        => 'text/html',
        'page_title'       => 'Please Login',
        'page_description' => 'Login page.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'man_logout' => [
        'url_id'           => 'man_logout',
        'ng_id'            => 'manager',
        'tpl_id'           => 'man_login',
        'page_type'        => 'text/html',
        'page_title'       => 'Logout',
        'page_description' => 'Logout page.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'verify_delete' => [
        'url_id'           => 'manager',
        'ng_id'            => 'manager',
        'tpl_id'           => 'verify_delete',
        'page_type'        => 'text/html',
        'page_title'       => 'Logout',
        'page_description' => 'Logout page.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'library' => [
        'url_id'           => 'library',
        'ng_id'            => 'config',
        'tpl_id'           => 'library',
        'page_type'        => 'text/html',
        'page_title'       => 'Advanced Config',
        'page_description' => 'Manages People, Places and Things',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_alias' => [
        'url_id'           => 'lib_alias',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_alias',
        'page_type'        => 'text/html',
        'page_title'       => 'URL Alias Manager',
        'page_description' => 'Manages the URL aliases',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_blocks' => [
        'url_id'           => 'lib_blocks',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_blocks',
        'page_type'        => 'text/html',
        'page_title'       => 'Blocks Manager',
        'page_description' => 'Manages the Blocks',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_cache' => [
        'url_id'           => 'lib_cache',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_cache',
        'page_type'        => 'text/html',
        'page_title'       => 'Cache Manager',
        'page_description' => 'Manages the Cache',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_constants' => [
        'url_id'           => 'lib_constants',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_constants',
        'page_type'        => 'text/html',
        'page_title'       => 'Constants Manager',
        'page_description' => 'Configuration for Constants',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_content' => [
        'url_id'           => 'lib_content',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_content',
        'page_type'        => 'text/html',
        'page_title'       => 'Content Manager',
        'page_description' => 'Configuration for Content',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_groups' => [
        'url_id'           => 'lib_groups',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_groups',
        'page_type'        => 'text/html',
        'page_title'       => 'Group Manager',
        'page_description' => 'Configuration for Groups',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_peeps' => [
        'url_id'           => 'lib_peeps',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_peeps',
        'page_type'        => 'text/html',
        'page_title'       => 'People Manager',
        'page_description' => 'Configuration for People',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_urls' => [
        'url_id'           => 'lib_urls',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_urls',
        'page_type'        => 'text/html',
        'page_title'       => 'Url Manager',
        'page_description' => 'Configuration for Urls',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_routes' => [
        'url_id'           => 'lib_routes',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_routes',
        'page_type'        => 'text/html',
        'page_title'       => 'Route Manager',
        'page_description' => 'Configuration for Routes',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_nav' => [
        'url_id'           => 'lib_nav',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_nav',
        'page_type'        => 'text/html',
        'page_title'       => 'Navigation Tools Manager',
        'page_description' => 'Configuration for Navigation tools',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_navgroups' => [
        'url_id'           => 'lib_navgroups',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_navgroups',
        'page_type'        => 'text/html',
        'page_title'       => 'Navgroups Manager',
        'page_description' => 'Configuration for Navgroups',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_pages' => [
        'url_id'           => 'lib_pages',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_pages',
        'page_type'        => 'text/html',
        'page_title'       => 'Page Manager',
        'page_description' => 'Configuration for pages, head information primarily',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_sitemap' => [
        'url_id'           => 'lib_sitemap',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_sitemap',
        'page_type'        => 'text/html',
        'page_title'       => 'Sitemap Manager',
        'page_description' => 'Sitemap Manager.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_twig' => [
        'url_id'           => 'lib_twig',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_twig',
        'page_type'        => 'text/html',
        'page_title'       => 'Template Configuration Manager',
        'page_description' => 'Configuration for Twig prefix, directories, and templates',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_tests' => [
        'url_id'           => 'lib_tests',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_test',
        'page_type'        => 'text/html',
        'page_title'       => 'Config Manager Tests',
        'page_description' => 'Runs tests for the Config Manager.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_test_results' => [
        'url_id'           => 'lib_test_results',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_test_results',
        'page_type'        => 'text/html',
        'page_title'       => 'Config Manager Tests Results',
        'page_description' => 'Returns the test results for the configuration section.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_login' => [
        'url_id'           => 'lib_login',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_login',
        'page_type'        => 'text/html',
        'page_title'       => 'Please Login',
        'page_description' => 'Login page.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
    'lib_logout' => [
        'url_id'           => 'lib_logout',
        'ng_id'            => 'config',
        'tpl_id'           => 'lib_login',
        'page_type'        => 'text/html',
        'page_title'       => 'Logout',
        'page_description' => 'Logout page.',
        'page_up'          => '1000-01-01 00:00:00',
        'page_down'        => '9999-12-31 23:59:59',
        'created_on'       => $sql_date,
        'updated_on'       => $sql_date,
        'page_base_url'    => '/',
        'page_lang'        => 'en',
        'page_charset'     => 'utf-8',
        'page_immutable'   => 'true'
    ],
];

$a_blocks = [
    'article' => [
        'b_name'      => 'article',
        'b_type'      => 'solo',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'body' => [
        'b_name'      => 'body',
        'b_type'      => 'solo',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'featured' => [
        'b_name'      => 'featured',
        'b_type'      => 'solo',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'instructions' => [
        'b_name'      => 'instructions',
        'b_type'      => 'solo',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'sidebar' => [
        'b_name'      => 'sidebar',
        'b_type'      => 'shared',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'header' => [
        'b_name'      => 'header',
        'b_type'      => 'shared',
        'b_active'    => 'true',
        'b_immutable' => 'true'
    ],
    'footer' => [
        'b_name'   => 'footer',
        'b_type'   => 'shared',
        'b_active' => 'true',
        'b_immutable' => 'true'
    ],
    'header_left' => [
        'b_name'   => 'header_left',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ],
    'header_middle' => [
        'b_name'   => 'header_middle',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ],
    'header_right' => [
        'b_name'   => 'header_right',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ],
    'footer_left' => [
        'b_name'   => 'footer_left',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ],
    'footer_middle' => [
        'b_name'   => 'footer_middle',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ],
    'footer_right' => [
        'b_name'   => 'footer_right',
        'b_type'   => 'shared',
        'b_active' => 'false',
        'b_immutable' => 'true'
    ]
];

$a_pbm = [
    'home_body' => [
        'pbm_page_id'  => 'home',
        'pbm_block_id' => 'body'
    ],
    'home_featured' => [
        'pbm_page_id'  => 'home',
        'pbm_block_id' => 'featured'
    ],
    'home_article' => [
        'pbm_page_id'  => 'home',
        'pbm_block_id' => 'article'
    ],
    'lib_alias_instructions' => [
        'pbm_page_id'  => 'lib_alias',
        'pbm_block_id' => 'instructions'
    ],
    'lib_blocks_instructions' => [
        'pbm_page_id'  => 'lib_blocks',
        'pbm_block_id' => 'instructions'
    ],
    'lib_cache_instructions' => [
        'pbm_page_id'  => 'lib_cache',
        'pbm_block_id' => 'instructions'
    ],
    'lib_constants_instructions' => [
        'pbm_page_id'  => 'lib_constants',
        'pbm_block_id' => 'instructions'
    ],
    'lib_content_instructions' => [
        'pbm_page_id'  => 'lib_content',
        'pbm_block_id' => 'instructions'
    ],
    'lib_groups_instructions' => [
        'pbm_page_id'  => 'lib_groups',
        'pbm_block_id' => 'instructions'
    ],
    'lib_nav_instructions' => [
        'pbm_page_id'  => 'lib_nav',
        'pbm_block_id' => 'instructions'
    ],
    'lib_navgroups_instructions' => [
        'pbm_page_id'  => 'lib_navgroups',
        'pbm_block_id' => 'instructions'
    ],
    'lib_pages_instructions' => [
        'pbm_page_id'  => 'lib_pages',
        'pbm_block_id' => 'instructions'
    ],
    'lib_peeps_instructions' => [
        'pbm_page_id'  => 'lib_peeps',
        'pbm_block_id' => 'instructions'
    ],
    'lib_routes_instructions' => [
        'pbm_page_id'  => 'lib_routes',
        'pbm_block_id' => 'instructions'
    ],
    'lib_sitemap_instructions' => [
        'pbm_page_id'  => 'lib_sitemap',
        'pbm_block_id' => 'instructions'
    ],
    'lib_tests_instructions' => [
        'pbm_page_id'  => 'lib_tests',
        'pbm_block_id' => 'instructions'
    ],
    'lib_twig_instructions' => [
        'pbm_page_id'  => 'lib_twig',
        'pbm_block_id' => 'instructions'
    ],
    'lib_urls_instructions' => [
        'pbm_page_id'  => 'lib_urls',
        'pbm_block_id' => 'instructions'
    ],
    'shared_sidebar' => [
        'pbm_page_id'  => 'shared',
        'pbm_block_id' => 'sidebar'
    ],
    'shared_header' => [
        'pbm_page_id'  => 'shared',
        'pbm_block_id' => 'header'
    ],
    'shared_footer' => [
        'pbm_page_id'  => 'shared',
        'pbm_block_id' => 'footer'
    ],
];

$a_content = [
   'home' => [
        'c_pbm_id'   => 'home_body',
        'c_content'  => "### Welcome.\nThis is the home page.\nThis example uses _Markdown_ which provides an _**easy**_ way to create basic **html**.",
        'c_type'     => 'md',
        'c_created'  => $sql_date,
        'c_updated'  => $sql_date,
        'c_version'  => '1',
        'c_current'  => 'true',
        'c_featured' => 'false'
   ],
   'lib_alias' => [
        'c_pbm_id'   => 'lib_alias_instructions',
        'c_content'  => '### Instuctions',
        'c_type'     => 'md',
        'c_created'  => $sql_date,
        'c_updated'  => $sql_date,
        'c_version'  => '1',
        'c_current'  => 'true',
        'c_featured' => 'false'
   ],
   'lib_blocks' => [
       'c_pbm_id'   => 'lib_blocks_instructions',
       'c_content'  => '### Instructions
- Block corresponds loosely to the blocks in a Twig template.
- Immutable prevents the record being deleted, renamed, or type changed _**so be careful**_.
       ',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_cache' => [
       'c_pbm_id'   => 'lib_cache_instructions',
       'c_content'  => '### Instuctions',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_constants' => [
       'c_pbm_id'   => 'lib_constants_instructions',
       'c_content'  => "### Instructions
- Constant Name must be letters only. The constant name will be converted to all caps, spaces to underscores, and all other characters removed, e.g., 'my 2nd config!' will become 'MY_ND_CONFIG'.
- Constant values will be sanitized (hacker prevention) but otherwise remain as typed. Values are limited to 64 characters.</li>
- Immutable means you can't change the Constant Name or delete it. <span class=\"red bold\">So be careful!</span> If you set something immutable, it can't go back.
        ",
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
    ],
   'lib_content' => [
       'c_pbm_id'   => 'lib_content_instructions',
       'c_content'  => '### Instruction',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_groups' => [
       'c_pbm_id'   => 'lib_groups_instructions',
       'c_content'  => '### Instructions
- Certain groups are fixed so that the app will work. They may not be visible based on auth level.</li>
- Group Name is a single word, alpha characters only. The app will change the group name to camelCase, then capitalizing the first letter, removing any other characters.
- Group Description can be anything that is meaningful. Text will be sanitized.
- Group Auth Level sets a general authorization level for the group, 0-9, 9 having the highest authorization level.
- Immutable means the name and auth level cannot be changed and the group can not be deleted. <span class="red bold">Be careful at what auth level you set!</span> Set it higher than your level and you can not unset immutable.
        ',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_peeps' => [
       'c_pbm_id'   => 'lib_peeps_instructions',
       'c_content'  => "### Instructions:
- Login ID is required. It is what is used for authentication and access. Immutable Login IDs are locked and should never be changed.
- Name is required. It can be anything. It is used for display. If left blank it will be set to the Login ID.
- Alias can be anything that is 8 characters or less and may be used in some views. Can be blank, it will be automagically created from Name.
- Password:
  - Is required
  - Is Encrypted and cannot be recovered, only replaced.
  - Must be at least 8 characters long.
  - Recommendation: It be at least 12 random or psuedo-random characters, upper and lower alpha, numeric and characters such as @% etc.
  - Recommendation: It not be a single dictionary word, name, or personal information.
  - Optionally a good practice would be to use 3 or 4 non-associated words of at least 4 characters long, e.g., 'Television river Banana' which allows one to remember the password while being difficult for a malicious attacker guess.
  - password and 12345678 are not good passwords. Come on! You don't want to be responsible for someone maliciously accessing this site and deface it.
- Description can be anything and is optional.
- Immutable prevents the person from being deleted as well as changing the Login ID.
- Active determines if the person is allowed to log in. Allows for historical data, keeping the person in the database but preventing one from logging in again.
- A group is required.
  - Groups allow the person to access the materials that group can access.
  - A person can be assigned to multiple groups but not necessarily needed - see next point.
  - Some groups are inclusive of other groups, e.g., Manager includes Editor and Registered. Anonymous is included in all groups and doesn't need to be selected ever.
        ",
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_urls' => [
       'c_pbm_id'   => 'lib_urls_instructions',
       'c_content'  => "### Instructions
- Urls may be in one of two formats
  - In proper (standards based) url format, e.g. https://my.fred.com/
  - Without protocol and server defaulting to the current site, e.g., /manager/config/urls/
  - Some filtering of the url will be made to try to make valid url format, e.g., spaces converted to underscores.
- Immutable means you can't delete it. You also cannot change the url.<span class=\"red bold\">So be careful!</span>
- If you have permission you can change immutable to off and make those changes.
        ",
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_routes' => [
       'c_pbm_id'   => 'lib_routes_instructions',
       'c_content'  => "### Instructions
- Routes map the url to a class, method, and action. Certain routes are fixed so that the app will work. They are not visible.
- Route Path is what appears in the browser address bar, e.g., '/about/charlie/'
  - Is taken from the URL manager.
  - The URL must be created first [Here](/manager/config/urls/)
- Route Class is the name of the class associated with the path, e.g., MasterController.
  - The class is required and must match the class name exactly.
  - The class is normally a controller.
- The method is the name of the method to be used.
  - The method is required.
  - The method must match the method name exactly.
- The action is normally an argument that is used in the method. This is optional.
        ",
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_nav' => [
       'c_pbm_id'   => 'lib_nav_instructions',
       'c_content'  => '### Instructions',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_pages' => [
       'c_pbm_id'   => 'lib_pages_instructions',
       'c_content'  => "### Instructions
- <span class=\"text-danger\">*</span> indicates a required field.
- Most values are used in the HTML meta tags, used in the head.
- The Page Url is unique per page. Only unused URLs are available. If you need a new url, you need to create it first [here](/manager/config/urls/) and normally then a new route [here](/manager/config/routes/)
- Page Title is used in the meta data but may also be used in a &lt;h1&gt;. If not specified, a default value may be used.
- Page Description can be pretty much any text. Can be blank. If not specified, a default value may be used. Per SEO, the descriptions should be no more than 150 characters.
- Base URL is a meta tag value. The default '/' is normally fine - the base site url will be used then.
- Mime Type is a meta tag value. The default 'text/html' is used for most web pages.
- Language is a meta tag value. The default is 'en' (English).
- Character Set is a meta tag value. The default is 'utf8' and should normally be used.
- Immutable means the page cannot be deleted nor the URL changed. _**Be careful!**_
- Twig Values - Choose a prefix first, then a directory, then the template. These values are created/managed [here](/manager/config/twig/).
  - Prefix refers to the grouping to which the template is assigned. Choosing a prefix will reset both directory and template.
  - Directory refers to the directory in the prefix grouping where the template physically resides. Changing a directory will reset the template.
  - Template is the actual template to be used for the page. A template is required for each page.
- Blocks specify which blocks are on the template and will be used for content. If you specify a block not on
  the template, it won't hurt anything but content created for it won't be displayed. If you don't specify a block that is on the template
  it won't hurt anything but content can't be created for the page. At least one block is required so if you do not
  specify one, the body block will used. Blocks are created/managed [here](/manager/config/blocks/).
- Navgroup specifies the primary navigation group to be displayed if used on the template. Navgroups are created/managed [here](/manager/config/navigation/)
- Publish On and Unpublish On allow one to have a page only appear between certain dates/times. If not specified page is always shown - defaults to 1000-01-01 00:00:00 and 9999-12-31 23:59:59
        ",
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_sitemap' => [
       'c_pbm_id'   => 'lib_sitemap_instructions',
       'c_content'  => '### Instructions',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_twig' => [
       'c_pbm_id'   => 'lib_twig_instructions',
       'c_content'  => '### Instructions',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ],
   'lib_tests' => [
       'c_pbm_id'   => 'lib_tests_instructions',
       'c_content'  => '### Instructions',
       'c_type'     => 'md',
       'c_created'  => $sql_date,
       'c_updated'  => $sql_date,
       'c_version'  => '1',
       'c_current'  => 'true',
       'c_featured' => 'false'
   ]
];

$a_twig_prefix = [
    'site' => [
        'tp_prefix'  => 'site_',
        'tp_path'    => '/src/templates',
        'tp_active'  => 'true',
        'tp_default' => 'true'
    ],
    'lib' => [
        'tp_prefix'  => 'lib_',
        'tp_path'    => '/src/apps/Ritc/Library/resources/templates',
        'tp_active'  => 'true',
        'tp_default' => 'false'
    ]
];

$a_twig_dirs = [
    'site_elements' => [
        'tp_id'   => 'site',
        'td_name' => 'elements'
    ],
    'site_forms' => [
        'tp_id'   => 'site',
        'td_name' => 'forms'
    ],
    'site_pages' => [
        'tp_id'   => 'site',
        'td_name' => 'pages'
    ],
    'site_snippets' => [
        'tp_id'     => 'site',
        'td_name'   => 'snippets'
    ],
    'site_tests'    => [
        'tp_id'     => 'site',
        'td_name'   => 'tests'
    ],
    'site_themes'    => [
        'tp_id'     => 'site',
        'td_name'   => 'themes'
    ],
    'lib_elements' => [
        'tp_id'    => 'lib',
        'td_name'  => 'elements'
    ],
    'lib_forms' => [
        'tp_id'   => 'lib',
        'td_name' => 'forms'
    ],
    'lib_pages' => [
        'tp_id'   => 'lib',
        'td_name' => 'pages'
    ],
    'lib_snippets' => [
        'tp_id'   => 'lib',
        'td_name' => 'snippets'
    ],
    'lib_tests'    => [
        'tp_id'     => 'lib',
        'td_name'   => 'tests'
    ],
    'lib_themes'  => [
        'tp_id'    => 'lib',
        'td_name'  => 'themes'
    ]
];

$a_twig_tpls = [
    'index' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'index',
        'tpl_immutable' => 'true'
    ],
    'man_login' =>  [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'login',
        'tpl_immutable' => 'true'
    ],
    'manager' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'manager',
        'tpl_immutable' => 'true'
    ],
    'verify_delete' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'verify_delete',
        'tpl_immutable' => 'true'
    ],
    'sitemap' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'sitemap',
        'tpl_immutable' => 'true'
    ],
    'error' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'error',
        'tpl_immutable' => 'true'
    ],
    'test' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'test',
        'tpl_immutable' => 'true'
    ],
    'man_test_results' => [
        'td_id'         => 'site_pages',
        'tpl_name'      => 'man_test_results',
        'tpl_immutable' => 'true'
    ],
    'library' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'index',
        'tpl_immutable' => 'true'
    ],
    'lib_alias' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'alias',
        'tpl_immutable' => 'true'
    ],
    'lib_blocks' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'blocks',
        'tpl_immutable' => 'true'
    ],
    'lib_cache' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'cache',
        'tpl_immutable' => 'true'
    ],
    'lib_content' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'content',
        'tpl_immutable' => 'true'
    ],
    'lib_constants' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'constants',
        'tpl_immutable' => 'true'
    ],
    'lib_error' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'error',
        'tpl_immutable' => 'true'
    ],
    'lib_groups' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'groups',
        'tpl_immutable' => 'true'
    ],
    'lib_login' =>  [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'login',
        'tpl_immutable' => 'true'
    ],
    'lib_nav' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'navigation_list',
        'tpl_immutable' => 'true'
    ],
    'lib_nav_form' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'navigation_form',
        'tpl_immutable' => 'true'
    ],
    'lib_navgroups' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'navgroups',
        'tpl_immutable' => 'true'
    ],
    'lib_pages' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'pages',
        'tpl_immutable' => 'true'
    ],
    'lib_page_form' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'page_form',
        'tpl_immutable' => 'true'
    ],
    'lib_peeps' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'people',
        'tpl_immutable' => 'true'
    ],
    'lib_person_form' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'person_form',
        'tpl_immutable' => 'true'
    ],
    'lib_routes' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'routes',
        'tpl_immutable' => 'true'
    ],
    'lib_shared' => [
        'td_id'         => 'lib_snippets',
        'tpl_name'      => 'shared',
        'tpl_immutable' => 'true'
    ],
    'lib_sitemap' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'lib_sitemap',
        'tpl_immutable' => 'true'
    ],
    'lib_tail' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'tail',
        'tpl_immutable' => 'true'
    ],
    'lib_test' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'test_list',
        'tpl_immutable' => 'true'
    ],
    'lib_test_results' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'man_test_results',
        'tpl_immutable' => 'true'
    ],
    'lib_twig' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'twig',
        'tpl_immutable' => 'true'
    ],
    'lib_urls' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'urls',
        'tpl_immutable' => 'true'
    ],
    'lib_vd' => [
        'td_id'         => 'lib_pages',
        'tpl_name'      => 'verify_delete',
        'tpl_immutable' => 'true'
    ]
];

$a_twig_default_dir_names = [
    'themes',
    'elements',
    'forms',
    'pages',
    'snippets',
    'tests'
];

return [
    'constants'         => $a_constants,
    'groups'            => $a_groups,
    'urls'              => $a_urls,
    'people'            => $a_people,
    'navgroups'         => $a_navgroups,
    'people_group_map'  => $a_people_group,
    'routes'            => $a_routes,
    'routes_group_map'  => $a_route_group_map,
    'navigation'        => $a_navigation,
    'nav_ng_map'        => $a_nav_ng_map,
    'page'              => $a_page,
    'blocks'            => $a_blocks,
    'page_block_map'    => $a_pbm,
    'content'           => $a_content,
    'twig_prefix'       => $a_twig_prefix,
    'twig_dirs'         => $a_twig_dirs,
    'twig_templates'    => $a_twig_tpls,
    'twig_default_dirs' => $a_twig_default_dir_names
];
