<?php

use \Tsugi\Util\U;

function settings_key_count() {
    global $CFG, $PDOX;
    $sql = "SELECT count(key_id) AS count
        FROM {$CFG->dbprefix}lti_key
        WHERE user_id = :UID";
    $key_count = 0;
    if ( U::get($_SESSION, 'id') ) {
        $row = $PDOX->rowDie($sql, array(':UID' => $_SESSION['id']));
        $key_count = U::get($row, 'count', 0);
    }
    return $key_count;
}

function settings_status($key_count) {
    global $CFG;
    if ( ! U::get($_SESSION,'id') ) {
        return "<p><b>You must log in to use these tools in your learning management system or Google Classroom.</b></p>";
    } else if ( U::get($_SESSION,'gc_count') ) {
        return "<p><b>You have access to ".U::get($_SESSION,'gc_count')." google classroom courses.  Use the icons below to install the tools in your classes.</b></p>";
    } else if ( ! U::get($_SESSION,'gc_count') && $key_count < 1 && 
        ( isset($CFG->providekeys) || isset($CFG->google_classroom_secret) ) ) {
        $retval = "<p><b>You need to ";
        if ( $CFG->providekeys ) {
            $retval .= 'have an approved <a href="'.$CFG->wwwroot.'/settings">LTI key</a>';
            if ( isset($CFG->google_classroom_secret) ) {
                $retval .= " or\n";
            }
        }
        if ( isset($CFG->google_classroom_secret) ) {
            $retval .= 'log in to <a href="'.$CFG->wwwroot.'/gclass/login">Google Classroom</a>';
        }
        $retval .= " to use these tools.</b></p>\n";
        return $retval;
    }
}
