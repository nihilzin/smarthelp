<?php

/**
 * ---------------------------------------------------------------------
 *
 * Powered by Urich Souza 
 *
 * https://github.com/nihilzin
 *
 * @copyright 2023 Urich Souza and contributors.
 * 
 * ---------------------------------------------------------------------
 */


if (strpos($_SERVER['PHP_SELF'], "dropdownFieldsBlacklist.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkRight("config", UPDATE);

$field = new Fieldblacklist();
if ($_POST['id'] > 0) {
    $field->getFromDB($_POST['id']);
} else {
    $field->getEmpty();
    $field->fields['itemtype'] = $_POST['itemtype'];
}
$field->selectCriterias();
