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

use Glpi\Event;

include('../inc/includes.php');

Session::checkCentralAccess();

$conn = new Computer_Item();

if (isset($_POST["disconnect"])) {
    $conn->check($_POST["id"], PURGE);
    $conn->delete($_POST, 1);
    Event::log(
        $_POST["computers_id"],
        "computers",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s disconnects an item'), $_SESSION["glpiname"])
    );
    Html::back();

   // Connect a computer to a printer/monitor/phone/peripheral
} else if (isset($_POST["add"])) {
    if (isset($_POST["items_id"]) && ($_POST["items_id"] > 0)) {
        $conn->check(-1, CREATE, $_POST);
        if ($conn->add($_POST)) {
            Event::log(
                $_POST["computers_id"],
                "computers",
                5,
                "inventory",
                //TRANS: %s is the user login
                sprintf(__('%s connects an item'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
}

Html::displayErrorAndDie('Lost');
