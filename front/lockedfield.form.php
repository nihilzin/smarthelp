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

Session::checkRight("locked_field", CREATE);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$lockedfield = new Lockedfield();

//Add a new locked field
if (isset($_POST["add"])) {
    $lockedfield->check(-1, UPDATE, $_POST);
    if ($newID = $lockedfield->add($_POST)) {
        Event::log(
            $newID,
            "lockedfield",
            4,
            "inventory",
            sprintf(__('%1$s adds global lock on %2$s'), $_SESSION["glpiname"], $_POST["item"])
        );

        if ($_SESSION['glpibackcreated']) {
            Html::redirect($lockedfield->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $lockedfield->check($_POST['id'], UPDATE);
    if ($lockedfield->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "lockedfield",
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $lockedfield->redirectToList();

   //update a locked field
} else if (isset($_POST["update"])) {
    $lockedfield->check($_POST['id'], UPDATE);
    $lockedfield->update($_POST);
    Event::log(
        $_POST["id"],
        "lockedfield",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {//print locked field information
    $menus = ["admin", "glpi\inventory\inventory", "lockedfield"];
    $lockedfield->displayFullPageForItem($_GET['id'], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
