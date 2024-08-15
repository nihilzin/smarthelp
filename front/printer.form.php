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

Session::checkRight("printer", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$print = new Printer();
if (isset($_POST["add"])) {
    $print->check(-1, CREATE, $_POST);

    if ($newID = $print->add($_POST)) {
        Event::log(
            $newID,
            "printers",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($print->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $print->check($_POST["id"], DELETE);
    $print->delete($_POST);

    Event::log(
        $_POST["id"],
        "printers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $print->redirectToList();
} else if (isset($_POST["restore"])) {
    $print->check($_POST["id"], DELETE);

    $print->restore($_POST);
    Event::log(
        $_POST["id"],
        "printers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $print->redirectToList();
} else if (isset($_POST["purge"])) {
    $print->check($_POST["id"], PURGE);

    $print->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "printers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $print->redirectToList();
} else if (isset($_POST["update"])) {
    $print->check($_POST["id"], UPDATE);

    $print->update($_POST);
    Event::log(
        $_POST["id"],
        "printers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["unglobalize"])) {
    $print->check($_POST["id"], UPDATE);

    Computer_Item::unglobalizeItem($print);
    Event::log(
        $_POST["id"],
        "printers",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s sets unitary management'), $_SESSION["glpiname"])
    );
    Html::redirect($print->getFormURLWithID($_POST["id"]));
} else {
    $menus = ["assets", "printer"];
    Printer::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
