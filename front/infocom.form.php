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

$ic = new Infocom();

if (isset($_POST['add'])) {
    $ic->check(-1, CREATE, $_POST);

    $newID = $ic->add($_POST, false);
    Event::log(
        $newID,
        "infocom",
        4,
        "financial",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $newID)
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $ic->check($_POST["id"], PURGE);
    $ic->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "infocom",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["update"])) {
    $ic->check($_POST["id"], UPDATE);

    $ic->update($_POST);
    Event::log(
        $_POST["id"],
        "infocom",
        4,
        "financial",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    Session::checkRight("infocom", READ);

    Html::popHeader(Infocom::getTypeName(), $_SERVER['PHP_SELF']);

    if (isset($_GET["id"])) {
        $ic->getFromDB($_GET["id"]);
        $_GET["itemtype"] = $ic->fields["itemtype"];
        $_GET["items_id"] = $ic->fields["items_id"];
    }
    $item = false;
    if (isset($_GET["itemtype"]) && ($item = getItemForItemtype($_GET["itemtype"]))) {
        if (!isset($_GET["items_id"]) || !$item->getFromDB($_GET["items_id"])) {
            $item = false;
        }
    }

    Infocom::showForItem($item, 0);

    Html::popFooter();
}
