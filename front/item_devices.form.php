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

if (isset($_POST["add"])) {
    Item_Devices::addDevicesFromPOST($_POST);
    Html::back();
} else if (isset($_POST["updateall"])) {
    Item_Devices::updateAll($_POST);
    Html::back();
}
Html::displayErrorAndDie('Lost');
