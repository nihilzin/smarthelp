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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

Session::checkLoginUser();

switch ($_REQUEST['action']) {
    case "getActors":
        header("Content-Type: application/json; charset=UTF-8");
        Html::header_nocache();
        Session::writeClose();
        echo Dropdown::getDropdownActors($_POST);
        break;
}
