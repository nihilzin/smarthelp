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

if (strpos($_SERVER['PHP_SELF'], "dropdownConnect.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

if (!isset($_POST['fromtype']) || !($fromitem = getItemForItemtype($_POST['fromtype']))) {
    exit();
}

$fromitem->checkGlobal(UPDATE);

$used = $_POST["used"] ?? [];
Computer_Item::dropdownConnect(
    $_POST["itemtype"],
    $_POST['fromtype'],
    $_POST['myname'],
    $_POST["entity_restrict"],
    $_POST["onlyglobal"],
    $used
);
