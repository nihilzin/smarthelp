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

Session::checkRight("phone", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$phone = new Phone();

if (isset($_POST["add"])) {
    $phone->check(-1, CREATE, $_POST);

    if ($newID = $phone->add($_POST)) {
        Event::log(
            $newID,
            "phones",
            4,
            "inventory",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($phone->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $phone->check($_POST["id"], DELETE);
    $phone->delete($_POST);

    Event::log(
        $_POST["id"],
        "phones",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $phone->redirectToList();
} else if (isset($_POST["restore"])) {
    $phone->check($_POST["id"], DELETE);

    $phone->restore($_POST);
    Event::log(
        $_POST["id"],
        "phones",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s restores an item'), $_SESSION["glpiname"])
    );
    $phone->redirectToList();
} else if (isset($_POST["purge"])) {
    $phone->check($_POST["id"], PURGE);

    $phone->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "phones",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $phone->redirectToList();
} else if (isset($_POST["update"])) {
    $phone->check($_POST["id"], UPDATE);

    $phone->update($_POST);
    Event::log(
        $_POST["id"],
        "phones",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["unglobalize"])) {
    $phone->check($_POST["id"], UPDATE);

    Computer_Item::unglobalizeItem($phone);
    Event::log(
        $_POST["id"],
        "phones",
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s sets unitary management'), $_SESSION["glpiname"])
    );

    Html::redirect($phone->getFormURLWithID($_POST["id"]));
} else {
    $menus = ['assets', 'phone'];
    Phone::displayFullPageForItem($_GET["id"], $menus, [
        'withtemplate' => $_GET["withtemplate"],
        'formoptions'  => "data-track-changes=true"
    ]);
}
