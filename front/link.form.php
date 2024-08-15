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

Session::checkRight("link", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$link = new Link();

if (isset($_POST["add"])) {
    $link->check(-1, CREATE);

    $newID = $link->add($_POST);
    Event::log(
        $newID,
        "links",
        4,
        "setup",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
    );
    Html::redirect(Toolbox::getItemTypeFormURL('Link') . "?id=" . $newID);
} else if (isset($_POST["purge"])) {
    $link->check($_POST["id"], PURGE);
    $link->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "links",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $link->redirectToList();
} else if (isset($_POST["update"])) {
    $link->check($_POST["id"], UPDATE);
    $link->update($_POST);
    Event::log(
        $_POST["id"],
        "links",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["config", "link"];
    Link::displayFullPageForItem($_GET["id"], $menus);
}
