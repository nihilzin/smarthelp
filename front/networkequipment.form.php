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

Session::checkRight("networking", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$netdevice = new NetworkEquipment();
if (isset($_POST["add"])) {
    $netdevice->check(-1, CREATE, $_POST);

    if ($newID = $netdevice->add($_POST)) {
        Event::log(
            $newID,
            "networkequipment",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($netdevice->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $netdevice->check($_POST["id"], DELETE);
    $netdevice->delete($_POST);

    Event::log(
        $_POST["id"],
        "networkequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );

    $netdevice->redirectToList();
} else if (isset($_POST["restore"])) {
    $netdevice->check($_POST["id"], DELETE);

    $netdevice->restore($_POST);
    Event::log(
        $_POST["id"],
        "networkequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $netdevice->redirectToList();
} else if (isset($_POST["purge"])) {
    $netdevice->check($_POST["id"], PURGE);

    $netdevice->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "networkequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $netdevice->redirectToList();
} else if (isset($_POST["update"])) {
    $netdevice->check($_POST["id"], UPDATE);

    $netdevice->update($_POST);
    Event::log(
        $_POST["id"],
        "networkequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["assets", "networkequipment"];
    NetworkEquipment::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
