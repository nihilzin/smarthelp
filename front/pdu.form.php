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

$pdu = new PDU();

if (isset($_POST["add"])) {
    $pdu->check(-1, CREATE, $_POST);

    if ($newID = $pdu->add($_POST)) {
        Event::log(
            $newID,
            "pdus",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($pdu->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $pdu->check($_POST["id"], DELETE);
    $pdu->delete($_POST);

    Event::log(
        $_POST["id"],
        "pdus",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $pdu->redirectToList();
} else if (isset($_POST["restore"])) {
    $pdu->check($_POST["id"], DELETE);

    $pdu->restore($_POST);
    Event::log(
        $_POST["id"],
        "pdus",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $pdu->redirectToList();
} else if (isset($_POST["purge"])) {
    $pdu->check($_POST["id"], PURGE);

    $pdu->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "pdus",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $pdu->redirectToList();
} else if (isset($_POST["update"])) {
    $pdu->check($_POST["id"], UPDATE);

    $pdu->update($_POST);
    Event::log(
        $_POST["id"],
        "pdus",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["assets", "pdu"];
    PDU::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
