<?php

define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASS', '');
define('DB_NAME', '');

define('USERS_FIELD_MAP', [
    'name' => 'name',
    'surname' => 'surname',
    'email' => 'email',
    'username' => 'username',
    'password' => 'password'
]);

define('PAGE_MAIN', 'index.php');
define('PAGE_SIGN_UP', 'sign_up.php');
define('PAGE_SIGN_IN', 'login.php');
define('PAGE_USER_INFO', 'user_info.php');
define('PAGE_LOGOUT', 'logout.php');
define('PAGE_EDIT_USER', 'edit_user.php');
define('PAGE_DASHBOARD', 'dashbboard.php');

define('TAG_USER_EDIT_BEHAVIOUR',              'user_edit_behaviour');
define('TAG_USER_EDIT_BEHAVIOUR_CREATION',     'user_edit_behaviour_creation');
define('TAG_USER_EDIT_BEHAVIOUR_EDITION',      'user_edit_behaviour_edition');
define('TAG_USER_EDIT_BEHAVIOUR_CONSULTATION', 'user_edit_behaviour_consultation');

define('TAG_PROJECT_NAME', 'YOUR PROJECT NAME');
?>
