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

Session::checkRight("datacenter", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$passive_equip = new PassiveDCEquipment();

if (isset($_POST["add"])) {
    $passive_equip->check(-1, CREATE, $_POST);

    if ($newID = $passive_equip->add($_POST)) {
        Event::log(
            $newID,
            "passivedcequipment",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($passive_equip->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $passive_equip->check($_POST["id"], DELETE);
    $passive_equip->delete($_POST);

    Event::log(
        $_POST["id"],
        "passivedcequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $passive_equip->redirectToList();
} else if (isset($_POST["restore"])) {
    $passive_equip->check($_POST["id"], DELETE);

    $passive_equip->restore($_POST);
    Event::log(
        $_POST["id"],
        "passivedcequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $passive_equip->redirectToList();
} else if (isset($_POST["purge"])) {
    $passive_equip->check($_POST["id"], PURGE);

    $passive_equip->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "passivedcequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $passive_equip->redirectToList();
} else if (isset($_POST["update"])) {
    $passive_equip->check($_POST["id"], UPDATE);

    $passive_equip->update($_POST);
    Event::log(
        $_POST["id"],
        "passivedcequipment",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $options = [
        'withtemplate' => $_GET['withtemplate'],
        'formoptions'  => "data-track-changes=true"
    ];
    if (isset($_GET['position'])) {
        $options['position'] = $_GET['position'];
    }
    if (isset($_GET['room'])) {
        $options['room'] = $_GET['room'];
    }

    $menus = ["assets", "passivedcequipment"];
    PassiveDCEquipment::displayFullPageForItem($_GET['id'], $menus, $options);
}
