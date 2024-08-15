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

Session::checkRight("cartridge", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$cartype = new CartridgeItem();

if (isset($_POST["add"])) {
    $cartype->check(-1, CREATE, $_POST);

    if ($newID = $cartype->add($_POST)) {
        Event::log(
            $newID,
            "cartridgeitems",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($cartype->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $cartype->check($_POST["id"], DELETE);

    if ($cartype->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "cartridgeitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $cartype->redirectToList();
} else if (isset($_POST["restore"])) {
    $cartype->check($_POST["id"], DELETE);

    if ($cartype->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "cartridgeitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $cartype->redirectToList();
} else if (isset($_POST["purge"])) {
    $cartype->check($_POST["id"], PURGE);

    if ($cartype->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "cartridgeitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $cartype->redirectToList();
} else if (isset($_POST["update"])) {
    $cartype->check($_POST["id"], UPDATE);

    if ($cartype->update($_POST)) {
        Event::log(
            $_POST["id"],
            "cartridgeitems",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["assets", "cartridgeitem"];
    CartridgeItem::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
