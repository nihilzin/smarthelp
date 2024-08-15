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

Session::checkRight("refusedequipment", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$refusedequipment = new RefusedEquipment();
if (isset($_POST["purge"])) {
    $refusedequipment->check($_POST["id"], PURGE);
    if ($refusedequipment->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "refusedequipment",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $refusedequipment->redirectToList();
} else if (isset($_POST["update"])) {
    $refusedequipment->check($_POST["id"], UPDATE);
    $refusedequipment->update($_POST);
    Event::log(
        $_POST["id"],
        "refusedequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["admin", "glpi\inventory\inventory", "refusedequipment"];
    RefusedEquipment::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
