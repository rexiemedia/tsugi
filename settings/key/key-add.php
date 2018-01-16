<?php
// In the top frame, we use cookies for session.
if (!defined('COOKIE_SESSION')) define('COOKIE_SESSION', true);
require_once("../../config.php");

use \Tsugi\UI\CrudForm;

\Tsugi\Core\LTIX::getConnection();

header('Content-Type: text/html; charset=utf-8');
session_start();

if ( ! isset($_SESSION['id']) ) {
    die('Must be logged in or admin');
}

$from_location = "keys";
$tablename = "{$CFG->dbprefix}lti_key";
$fields = array("key_key", "key_sha256", "secret", "created_at", "updated_at");

$retval = CrudForm::handleInsert($tablename, $fields);
if ( $retval == CrudForm::CRUD_SUCCESS || $retval == CrudForm::CRUD_FAIL ) {
    header("Location: $from_location");
    return;
}

$OUTPUT->header();
$OUTPUT->bodyStart();
$OUTPUT->topNav();
$OUTPUT->flashMessages();

echo("<h1>Adding Key Entry</h1>\n<p>\n");

CrudForm::insertForm($fields, $from_location);

echo("</p>\n");

$OUTPUT->footer();

