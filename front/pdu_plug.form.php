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

$pdup = new \Pdu_Plug();
$pdu = new PDU();

if (isset($_POST['update'])) {
    $pdup->check($_POST['id'], UPDATE);
   //update existing relation
    if ($pdup->update($_POST)) {
        $url = $pdu->getFormURLWithID($_POST['pdus_id']);
    } else {
        $url = $pdup->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $pdup->check(-1, CREATE, $_POST);
    $pdup->add($_POST);
    $url = $pdu->getFormURLWithID($_POST['pdus_id']);
    Html::redirect($url);
} else if (isset($_POST['purge'])) {
    $pdup->check($_POST['id'], PURGE);
    $pdup->delete($_POST, 1);
    $url = $pdu->getFormURLWithID($_POST['pdus_id']);
    Html::redirect($url);
}

if (!isset($_GET['pdus_id']) && !isset($_GET['plugs_id']) && !isset($_GET['number_plug']) && !isset($_GET['id'])) {
    Html::displayErrorAndDie('Lost');
}

$params = [];
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
} else {
    $params = [
        'pdus_id'      => $_GET['pdus_id'],
        'plugs_id'     => $_GET['plugs_id'],
        'number_plug'  => $_GET['number_plug']
    ];
}
$ajax = isset($_REQUEST['ajax']) ? true : false;

if ($ajax) {
    $pdup->display($params);
} else {
    $menus = ["assets", "pdu"];
    Pdu_Plug::displayFullPageForItem($_GET['id'] ?? 0, $menus, $params);
}
