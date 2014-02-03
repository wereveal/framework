<?php
/*
Test Order
    'test_order' => array(
        'readConfig',
        'createConfig',
        'updateConfig',
        'deleteConfig'
    )
*/
return array(
    'test_order' => array(
        'readConfig',
        'createConfig',
        'updateConfig',
        'deleteConfig'
    ),
    'all_configs' => array(
        array(
            'config_id'    => '1',
            'config_name'  => 'USER_ID',
            'config_value' => '1001'
        ),
        array(
            'config_id'    => '2',
            'config_name'  => 'GROUP_ID',
            'config_value' => '81'
        ),
        array(
            'config_id'    => '3',
            'config_name'  => 'FTP_BASE_PATH',
            'config_value' => '/srv/websites'
        ),
        array(
            'config_id'    => '4',
            'config_name'  => 'DISPLAY_DATE_FORMAT',
            'config_value' => 'm/d/Y'
        ),
        array(
            'config_id'    => '5',
            'config_name'  => 'EMAIL_DOMAIN',
            'config_value' => 'revealitconsulting.com'
        ),
        array(
            'config_id'    => '6',
            'config_name'  => 'EMAIL_FORM_TO',
            'config_value' => 'bill@revealitconsulting.com'
        ),
        array(
            'config_id'    => '7',
            'config_name'  => 'ERROR_EMAIL_ADDRESS',
            'config_value' => 'webmaster@revealitconsulting.com'
        ),
        array(
            'config_id'    => '8',
            'config_name'  => 'PAGE_META_DESCRIPTION',
            'config_value' => 'Reveal IT Consulting'
        ),
        array(
            'config_id'    => '9',
            'config_name'  => 'PAGE_META_KEYWORDS',
            'config_value' => 'Reveal IT Consulting'
        ),
        array(
            'config_id'    => '10',
            'config_name'  => 'PAGE_TEMPLATE',
            'config_value' => 'index.twig'
        ),
        array(
            'config_id'    => '11',
            'config_name'  => 'PAGE_TITLE',
            'config_value' => 'Reveal IT Consulting'
        ),
        array(
            'config_id'    => '12',
            'config_name'  => 'THEME_NAME',
            'config_value' => 'default'
        ),
        array(
            'config_id'    => '13',
            'config_name'  => 'ADMIN_THEME_NAME',
            'config_value' => 'default'
        ),
        array(
            'config_id'    => '14',
            'config_name'  => 'ENCRYPT_TYPE',
            'config_value' => 'sha1'
        ),
    ),
    'single_config' => array(
        'config_id'    => '1',
        'config_name'  => 'USER_ID',
        'config_value' => '1001'
    ),
    'new_config' => array(
        'config_name'  => 'TEST_CONFIG',
        'config_value' => 'This is a test'
    ),
    'modified_config' => array(
        'config_name'  => 'TEST_CONFIG',
        'config_value' => 'This is a modified test'
    )
);
