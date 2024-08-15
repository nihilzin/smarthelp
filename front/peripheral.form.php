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

Session::checkRight("peripheral", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$peripheral = new Peripheral();

if (isset($_POST["add"])) {
    $peripheral->check(-1, CREATE, $_POST);

    if ($newID = $peripheral->add($_POST)) {
        Event::log(
            $newID,
            "peripherals",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($peripheral->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $peripheral->check($_POST["id"], DELETE);
    $peripheral->delete($_POST);

    Event::log(
        $_POST["id"],
        "peripherals",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $peripheral->redirectToList();
} else if (isset($_POST["restore"])) {
    $peripheral->check($_POST["id"], DELETE);

    $peripheral->restore($_POST);
    Event::log(
        $_POST["id"],
        "peripherals",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $peripheral->redirectToList();
} else if (isset($_POST["purge"])) {
    $peripheral->check($_POST["id"], PURGE);

    $peripheral->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "peripherals",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $peripheral->redirectToList();
} else if (isset($_POST["update"])) {
    $peripheral->check($_POST["id"], UPDATE);

    $peripheral->update($_POST);
    Event::log(
        $_POST["id"],
        "peripherals",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["unglobalize"])) {
    $peripheral->check($_POST["id"], UPDATE);

    Computer_Item::unglobalizeItem($peripheral);
    Event::log(
        $_POST["id"],
        "peripherals",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s sets unitary management'), $_SESSION["glpiname"])
    );

    Html::redirect($peripheral->getFormURLWithID($_POST["id"]));
} else {
    $menus = ["assets", "peripheral"];
    Peripheral::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
