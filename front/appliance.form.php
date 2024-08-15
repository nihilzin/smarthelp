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

Session::checkRight('appliance', READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$app = new Appliance();

if (isset($_POST["add"])) {
    $app->check(-1, CREATE, $_POST);

    if ($newID = $app->add($_POST)) {
        Event::log(
            $newID,
            "appliance",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($app->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $app->check($_POST["id"], DELETE);
    $app->delete($_POST);

    Event::log(
        $_POST["id"],
        "appliance",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $app->redirectToList();
} else if (isset($_POST["restore"])) {
    $app->check($_POST["id"], DELETE);

    $app->restore($_POST);
    Event::log(
        $_POST["id"],
        "appliance",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $app->redirectToList();
} else if (isset($_POST["purge"])) {
    $app->check($_POST["id"], PURGE);

    $app->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "appliance",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $app->redirectToList();
} else if (isset($_POST["update"])) {
    $app->check($_POST["id"], UPDATE);

    $app->update($_POST);
    Event::log(
        $_POST["id"],
        "appliance",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $menus = ["management", "appliance"];
    Appliance::displayFullPageForItem($_GET['id'], $menus, [
        'withtemplate' => $_GET['withtemplate']
    ]);
}
