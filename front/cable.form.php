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

Session::checkRight("cable_management", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = '';
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = '';
}

$cable = new Cable();
if (isset($_POST["add"])) {
    $cable->check(-1, CREATE, $_POST);

    if ($newID = $cable->add($_POST)) {
        Event::log(
            $newID,
            "cable",
            4,
            "management",
            //TRANS: %1$s is the user login, %2$s is the name of the item to add
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($cable->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $cable->check($_POST["id"], DELETE);

    if ($cable->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "cable",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $cable->redirectToList();
} else if (isset($_POST["restore"])) {
    $cable->check($_POST["id"], DELETE);

    if ($cable->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "cable",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $cable->redirectToList();
} else if (isset($_POST["purge"])) {
    $cable->check($_POST["id"], PURGE);

    if ($cable->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "cable",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $cable->redirectToList();
} else if (isset($_POST["update"])) {
    $cable->check($_POST["id"], UPDATE);

    if ($cable->update($_POST)) {
        Event::log(
            $_POST["id"],
            "cable",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else if (isset($_GET['_in_modal'])) {
      Html::popHeader(Cable::getTypeName(1), $_SERVER['PHP_SELF'], true);
      $cable->showForm($_GET["id"], ['withtemplate' => $_GET["withtemplate"]]);
      Html::popFooter();
} else {
    $menus = ["assets", "cable"];
    Cable::displayFullPageForItem($_GET['id'], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
