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

$ios = new \Item_OperatingSystem();

if (isset($_POST['update'])) {
    $ios->check($_POST['id'], UPDATE);
   //update existing OS
    $ios->update($_POST);

    $item = getItemForItemtype($_POST['itemtype']);
    $url = $item->getFormURLWithID($_POST['items_id']);
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $ios->check(-1, CREATE, $_POST);
    $ios->add($_POST);

    $item = getItemForItemtype($_POST['itemtype']);
    $url = $item->getFormURLWithID($_POST['items_id']);
    Html::redirect($url);
} else if (isset($_POST['purge'])) {
    $ios->check($_POST['id'], PURGE);
    $ios->delete($_POST, 1);

    $item = getItemForItemtype($_POST['itemtype']);
    $url = $item->getFormURLWithID($_POST['items_id']);
    Html::redirect($url);
}

if (!isset($_GET['itemtype']) && !isset($_GET['items_id']) && !isset($_GET['id'])) {
    Html::displayErrorAndDie('Lost');
}

$params = [];
if (isset($_GET['id'])) {
    $params['id'] = $_GET['id'];
} else {
    $params = [
        'itemtype'  => $_GET['itemtype'],
        'items_id'  => $_GET['items_id']
    ];
}

$menus = ["assets", "computer"];
Item_OperatingSystem::displayFullPageForItem($params['id'] ?? 0, $menus, $params);
