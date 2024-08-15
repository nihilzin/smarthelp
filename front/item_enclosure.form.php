<?php

/*!
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

include('../inc/includes.php');

Session::checkCentralAccess();

$ien = new \Item_Enclosure();
$enclosure = new Enclosure();

if (isset($_POST['update'])) {
    $ien->check($_POST['id'], UPDATE);
   //update existing relation
    if ($ien->update($_POST)) {
        $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    } else {
        $url = $ien->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $ien->check(-1, CREATE, $_POST);
    $ien->add($_POST);
    $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    Html::redirect($url);
} else if (isset($_POST['purge'])) {
    $ien->check($_POST['id'], PURGE);
    $ien->delete($_POST, 1);
    $url = $enclosure->getFormURLWithID($_POST['enclosures_id']);
    Html::redirect($url);
}

if (!isset($_REQUEST['enclosure']) && !isset($_REQUEST['id'])) {
    Html::displayErrorAndDie('Lost');
}

$params = [];
if (isset($_REQUEST['id'])) {
    $params['id'] = $_REQUEST['id'];
} else {
    $params = [
        'enclosures_id'   => $_REQUEST['enclosure']
    ];
}

$menus = ["management", "enclosure"];
Item_Enclosure::displayFullPageForItem($_REQUEST['id'] ?? 0, $menus, $params);
