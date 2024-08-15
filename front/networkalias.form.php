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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

$alias = new NetworkAlias();

if (!isset($_GET["id"])) {
    $_GET["id"] = -1;
}
if (empty($_GET["networknames_id"])) {
    $_GET["networknames_id"] = "";
}

if (isset($_POST["add"])) {
    $alias->check(-1, CREATE, $_POST);

    if ($newID = $alias->add($_POST)) {
        Event::log(
            $newID,
            $alias->getType(),
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($alias->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $alias->check($_POST['id'], PURGE);
    $item = $alias->getItem();
    $alias->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "networkname",
        5,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    if ($item) {
        Html::redirect($item->getLinkURL());
    } else {
        Html::redirect($CFG_GLPI["root_doc"] . "/front/central.php");
    }
} else if (isset($_POST["update"])) {
    $alias->check($_POST["id"], UPDATE);
    $alias->update($_POST);

    Event::log(
        $_POST["id"],
        $alias->getType(),
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
}

if (isset($_GET['_in_modal'])) {
    Html::popHeader(NetworkAlias::getTypeName(1), $_SERVER['PHP_SELF']);
    $alias->showForm($_GET["id"], $_GET);
    Html::popFooter();
} else {
    if (!isset($_GET["id"])) {
        $_GET["id"] = "";
    }

    Session::checkRight("internet", UPDATE);

    $menus = ['assets'];
    NetworkAlias::displayFullPageForItem($_GET["id"], $menus, $_GET);
}
