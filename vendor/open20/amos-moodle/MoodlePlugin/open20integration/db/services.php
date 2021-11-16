<?php

defined('MOODLE_INTERNAL') || die();

$functions = array(
    'local_open20integration_get_course_badges' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'get_course_badges',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Returns a courses\' badges',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        //'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
    'local_open20integration_get_course_img' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'get_course_img',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Returns the course img url',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        //'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
    'local_open20integration_get_courses_imgs' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'get_courses_imgs',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Returns the courses imgs urls',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        //'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
    'local_open20integration_get_scorm_data_by_cm' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'get_scorm_data_by_cm',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Returns the scorm attempts data and player url',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
        //'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
    'local_open20integration_add_siteadmin' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'add_siteadmin',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Add a new user to the list of site admins',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
        //'services' => array(MOODLE_OFFICIAL_MOBILE_SERVICE)    // Optional, only available for Moodle 3.1 onwards. List of built-in services (by shortname) where the function will be included.  Services created manually via the Moodle interface are not supported.
    ),
    'local_open20integration_generate_user_token' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'generate_user_token',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Generate a new token for specific service and user. Even siteadmins',    //human readable description of the web service function
        'type'        => 'write',                  //database rights of the web service function (read, write)
    ),

    'local_open20integration_get_enrol_info' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'get_enrol_info',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Get info about enrol course',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
    ),

    'local_open20integration_enrol_user_via_paypal' => array(         //web service function name
        'classname'   => 'local_open20integration_external',  //class containing the external function
        'methodname'  => 'enrol_user_via_paypal',          //external function name
        'classpath'   => 'local/open20integration/externallib.php',  //file containing the class/external function
        'description' => 'Set enrol course for paypal usere',    //human readable description of the web service function
        'type'        => 'read',                  //database rights of the web service function (read, write)
    ),

);