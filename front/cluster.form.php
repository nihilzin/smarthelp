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

Session::checkRight("cluster", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$cluster = new Cluster();

if (isset($_POST["add"])) {
    $cluster->check(-1, CREATE, $_POST);

    if ($newID = $cluster->add($_POST)) {
        Event::log(
            $newID,
            "cluster",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($cluster->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $cluster->check($_POST["id"], DELETE);
    $cluster->delete($_POST);

    Event::log(
        $_POST["id"],
        "cluster",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $cluster->redirectToList();
} else if (isset($_POST["restore"])) {
    $cluster->check($_POST["id"], DELETE);

    $cluster->restore($_POST);
    Event::log(
        $_POST["id"],
        "cluster",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $cluster->redirectToList();
} else if (isset($_POST["purge"])) {
    $cluster->check($_POST["id"], PURGE);

    $cluster->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "cluster",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $cluster->redirectToList();
} else if (isset($_POST["update"])) {
    $cluster->check($_POST["id"], UPDATE);

    $cluster->update($_POST);
    Event::log(
        $_POST["id"],
        "cluster",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else {
    $options = [
        'withtemplate' => $_GET['withtemplate'],
        'formoptions'  => "data-track-changes=true",
    ];
    if (isset($_GET['position'])) {
        $options['position'] = $_GET['position'];
    }
    if (isset($_GET['room'])) {
        $options['room'] = $_GET['room'];
    }
    $menus = ["management", "cluster"];
    Cluster::displayFullPageForItem($_GET['id'], $menus, $options);
}
