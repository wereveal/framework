<?php
/*
   Test Order
    'test_order' => array(
        'findAll',
        'findUserById',
        'createUser',
        'updateUser',
        'updateUserDir',
        'updateUserPassword',
        'deleteUser',
    )
 */
return array(
    'test_order' => array(
        'findAll',
        'findUserById',
        'createUser',
        'updateUser',
        'updateUserDir',
        'updateUserPassword',
        'deleteUser'
    ),
    'all_users' => array(
        array(
            'id'       => 'test1',
            'password' => 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3',
            'uid'      => '1001',
            'gid'      => '81',
            'dir'      => '/srv/websites/test1/./'
        ),
        array(
            'id'       => 'test2',
            'password' => '291afba23901779715f0256d34ab29faa657a0c1',
            'uid'      => '1001',
            'gid'      => '81',
            'dir'      => '/srv/websites/test2/./'
        )
    ),
    'single_user' => array(
        'id'       => 'test1',
        'password' => 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3',
        'uid'      => '1001',
        'gid'      => '81',
        'dir'      => '/srv/websites/test1/./'
    ),
    'new_user' => array(
        'id'       => 'test3',
        'password' => 'letmein',
        'uid'      => '1001',
        'gid'      => '81',
        'dir'      => '/srv/websites/test3/./'
    ),
    'new_user_hashed' => array(
        'id'       => 'test3',
        'password' => 'b7a875fc1ea228b9061041b7cec4bd3c52ab3ce3',
        'uid'      => '1001',
        'gid'      => '81',
        'dir'      => '/srv/websites/test3/./'
    ),
    'modified_user' => array(
        'id'       => 'test3',
        'password' => 'letnoonein'
    ),
    'modified_user_hashed' => array(
        'id'       => 'test3',
        'password' => 'ae993b8419c135a55a5c7dd5571435236fc979e0',
        'uid'      => '1001',
        'gid'      => '81',
        'dir'      => '/srv/websites/test3/./'
    ),
    'modified_user_dir' => array(
        'id'       => 'test3',
        'dir'      => '/srv/websites/test3_1/./'
    ),
);
