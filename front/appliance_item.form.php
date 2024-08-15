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

$iapp = new \Appliance_Item();
$app = new Appliance();

if (isset($_POST['update'])) {
    $iapp->check($_POST['id'], UPDATE);
   //update existing relation
    if ($iapp->update($_POST)) {
        $url = $app->getFormURLWithID($_POST['appliances_id']);
    } else {
        $url = $iapp->getFormURLWithID($_POST['id']);
    }
    Html::redirect($url);
} else if (isset($_POST['add'])) {
    $iapp->check(-1, CREATE, $_POST);
    $iapp->add($_POST);
    Html::back();
} else if (isset($_POST['purge'])) {
    $iapp->check($_POST['id'], PURGE);
    $iapp->delete($_POST, 1);
    $url = $app->getFormURLWithID($_POST['appliances_id']);
    Html::redirect($url);
}

Html::displayErrorAndDie("lost");
