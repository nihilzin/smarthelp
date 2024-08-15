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

Session::checkRight('database', READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$instance = new DatabaseInstance();
if (isset($_POST["add"])) {
    $instance->check(-1, CREATE, $_POST);

    if ($newID = $instance->add($_POST)) {
        Event::log(
            $newID,
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s adds a database instance'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($instance->getLinkURL());
        }
    }
    Html::back();
} elseif (isset($_POST["delete"])) {
    $instance->check($_POST['id'], DELETE);
    $ok = $instance->delete($_POST);
    if ($ok) {
        Event::log(
            $_POST["id"],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s deletes a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["restore"])) {
    $instance->check($_POST['id'], DELETE);
    if ($instance->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s restores a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["purge"])) {
    $instance->check($_POST["id"], PURGE);

    if ($instance->delete($_POST, 1)) {
        Event::log(
            $_POST['id'],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s purges a database instance'), $_SESSION["glpiname"])
        );
    }
    $instance->redirectToList();
} elseif (isset($_POST["update"])) {
    $instance->check($_POST["id"], UPDATE);

    if ($instance->update($_POST)) {
        Event::log(
            $_POST['id'],
            "databaseinstance",
            4,
            "management",
            //TRANS: %s is the user login
            sprintf(__('%s updates a database instance'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    Html::header(
        DatabaseInstance::getTypeName(Session::getPluralNumber()),
        $_SERVER['PHP_SELF'],
        "management",
        "database",
        "databaseinstance"
    );

    $menus = ["database", "databaseinstance"];
    DatabaseInstance::displayFullPageForItem($_GET['id'], $menus, [
        'withtemplate' => $_GET['withtemplate']
    ]);
}
