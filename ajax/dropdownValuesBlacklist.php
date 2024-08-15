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

if (strpos($_SERVER['PHP_SELF'], "dropdownValuesBlacklist.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

Session::checkRight("config", UPDATE);
if (
    isset($_POST['itemtype'])
    && isset($_POST['id_field'])
) {
    $blacklist = new Fieldblacklist();
    if (isset($_POST['id']) && ($_POST['id'] > 0)) {
        $blacklist->getFromDB($_POST['id']);
    } else {
        $blacklist->getEmpty();
    }
    $blacklist->fields['field']    = $_POST['id_field'];
    $blacklist->fields['itemtype'] = $_POST['itemtype'];
    $blacklist->selectValues($_POST['id_field']);
}
