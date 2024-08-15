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

$pra  = new \PDU_Rack();
$rack = new Rack();

if (isset($_POST['update'])) {
    $pra->check($_POST['id'], UPDATE);
   //update existing relation
    if ($pra->update($_POST)) {
        $url = $rack->getFormURLWithID($_POST['racks_id']);
    } else {
        $url = $pra->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $pra->check(-1, CREATE, $_POST);
    $pra->add($_POST);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
} else if (isset($_POST['purge'])) {
    $pra->check($_POST['id'], PURGE);
    $pra->delete($_POST, 1);
    $url = $rack->getFormURLWithID($_POST['racks_id']);
    Html::redirect($url);
}

$params = [];
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
} else {
    $params = [
        'racks_id'     => $_GET['racks_id'],
    ];
}

$_SESSION['glpilisturl'][PDU_Rack::getType()] = $rack->getSearchURL();

$ajax = isset($_REQUEST['ajax']) ? true : false;

if ($ajax) {
    $pra->display($params);
} else {
    $menus = ["assets", "rack"];
    PDU_Rack::displayFullPageForItem($_GET['id'] ?? 0, $menus, $params);
}
