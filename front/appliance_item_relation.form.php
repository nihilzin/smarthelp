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

$app_item_rel = new Appliance_Item_Relation();

if (isset($_POST['add'])) {
    $app_item_rel->check(-1, CREATE, $_POST);
    $app_item_rel->add($_POST);
    Html::back();
} else if (isset($_POST['purge'])) {
    $app_item_rel->check($_POST['id'], PURGE);
    $app_item_rel->delete($_POST, 1);
    Html::back();
}

Html::displayErrorAndDie("lost");
