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

// Direct access to file
if (strpos($_SERVER['PHP_SELF'], "dropdownDelegationUsers.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

$_POST['_users_id_requester'] = 0;
$_POST['_right'] = "delegate";
if ($_POST["nodelegate"] == 1) {
    $_POST['_users_id_requester'] = Session::getLoginUserID();
    $_POST['_right']              = "id";
}

$ticket = new Ticket();
$ticket->showActorAddFormOnCreate(Ticket_User::REQUESTER, $_POST);
