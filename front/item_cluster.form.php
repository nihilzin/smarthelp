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

$icl = new \Item_Cluster();
$cluster = new Cluster();

if (isset($_POST['update'])) {
    $icl->check($_POST['id'], UPDATE);
   //update existing relation
    if ($icl->update($_POST)) {
        $url = $cluster->getFormURLWithID($_POST['clusters_id']);
    } else {
        $url = $icl->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $icl->check(-1, CREATE, $_POST);
    $icl->add($_POST);
    $url = $cluster->getFormURLWithID($_POST['clusters_id']);
    Html::redirect($url);
} else if (isset($_POST['purge'])) {
    $icl->check($_POST['id'], PURGE);
    $icl->delete($_POST, 1);
    $url = $cluster->getFormURLWithID($_POST['clusters_id']);
    Html::redirect($url);
}

if (!isset($_REQUEST['cluster']) && !isset($_REQUEST['id'])) {
    Html::displayErrorAndDie('Lost');
}

$params = [];
if (isset($_REQUEST['id'])) {
    $params['id'] = $_REQUEST['id'];
} else {
    $params = [
        'clusters_id'   => $_REQUEST['cluster']
    ];
}

$menus = ["management", "cluster"];
Item_Cluster::displayFullPageForItem($params['id'] ?? 0, $menus, $params);
