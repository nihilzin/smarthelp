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

$note = new Notepad();

if (isset($_POST['add'])) {
    $note->check(-1, CREATE, $_POST);

    $newID = $note->add($_POST, false);
    Event::log(
        $newID,
        "notepad",
        4,
        "tools",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $newID)
    );
    Html::back();
} else if (isset($_POST["purge"])) {
    $note->check($_POST["id"], PURGE);
    $note->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "notepad",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["update"])) {
    $note->check($_POST["id"], UPDATE);

    $note->update($_POST);
    Event::log(
        $_POST["id"],
        "notepad",
        4,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
}

if (isset($_GET['id']) && $note->getFromDB($_GET['id'])) {
    /** @var class-string<CommonDBTM> $parent_itemtype */
    $parent_itemtype = $note->fields['itemtype'];
    $redirect = $parent_itemtype::getFormURLWithID($note->fields['items_id'], true) . "&forcetab=Notepad$1";
    Html::redirect($redirect);
} else {
    Html::displayErrorAndDie("lost");
}
