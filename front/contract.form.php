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

Session::checkRight("contract", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = -1;
}

if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$contract         = new Contract();

if (isset($_POST["add"])) {
    $contract->check(-1, CREATE, $_POST);

    if ($newID = $contract->add($_POST)) {
        Event::log(
            $newID,
            "contracts",
            4,
            "financial",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($contract->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $contract->check($_POST['id'], DELETE);

    if ($contract->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $contract->redirectToList();
} else if (isset($_POST["restore"])) {
    $contract->check($_POST['id'], DELETE);

    if ($contract->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $contract->redirectToList();
} else if (isset($_POST["purge"])) {
    $contract->check($_POST['id'], PURGE);

    if ($contract->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $contract->redirectToList();
} else if (isset($_POST["update"])) {
    $contract->check($_POST['id'], UPDATE);

    if ($contract->update($_POST)) {
        Event::log(
            $_POST["id"],
            "contracts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["management", "contract"];
    Contract::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
