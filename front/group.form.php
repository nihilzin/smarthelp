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

Session::checkRight("group", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}

$group = new Group();

if (isset($_POST["add"])) {
    $group->check(-1, CREATE, $_POST);
    if ($newID = $group->add($_POST)) {
        Event::log(
            $newID,
            "groups",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($group->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $group->check($_POST["id"], PURGE);
    if (
        $group->isUsed()
         && empty($_POST["forcepurge"])
    ) {
        Html::header(
            $group->getTypeName(1),
            $_SERVER['PHP_SELF'],
            "admin",
            "group",
            str_replace('glpi_', '', $group->getTable())
        );

        $group->showDeleteConfirmForm($_SERVER['PHP_SELF']);
        Html::footer();
    } else {
        $group->delete($_POST, 1);
        Event::log(
            $_POST["id"],
            "groups",
            4,
            "setup",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
        $group->redirectToList();
    }
} else if (isset($_POST["update"])) {
    $group->check($_POST["id"], UPDATE);
    $group->update($_POST);
    Event::log(
        $_POST["id"],
        "groups",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_GET['_in_modal'])) {
    Html::popHeader(Group::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], true);
    $group->showForm($_GET["id"]);
    Html::popFooter();
} else if (isset($_POST["replace"])) {
    $group->check($_POST["id"], PURGE);
    $group->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "groups",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s replaces an item'), $_SESSION["glpiname"])
    );
    $group->redirectToList();
} else {
    $menus = ["admin", "group"];
    Group::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
