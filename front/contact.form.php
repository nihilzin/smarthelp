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

Session::checkRight("contact_enterprise", READ);

if (empty($_GET["id"])) {
    $_GET["id"] = -1;
}

$contact = new Contact();

if (isset($_GET['getvcard'])) {
    if ($_GET["id"] < 0) {
        Html::redirect($CFG_GLPI["root_doc"] . "/front/contact.php");
    }
    $contact->check($_GET["id"], READ);
    $contact->generateVcard();
} else if (isset($_POST["add"])) {
    $contact->check(-1, CREATE, $_POST);

    if ($newID = $contact->add($_POST)) {
        Event::log(
            $newID,
            "contacts",
            4,
            "financial",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($contact->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $contact->check($_POST["id"], DELETE);

    if ($contact->delete($_POST)) {
        Event::log(
            $_POST["id"],
            "contacts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
        );
    }
    $contact->redirectToList();
} else if (isset($_POST["restore"])) {
    $contact->check($_POST["id"], DELETE);

    if ($contact->restore($_POST)) {
        Event::log(
            $_POST["id"],
            "contacts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s restores an item'), $_SESSION["glpiname"])
        );
    }
    $contact->redirectToList();
} else if (isset($_POST["purge"])) {
    $contact->check($_POST["id"], PURGE);

    if ($contact->delete($_POST, 1)) {
        Event::log(
            $_POST["id"],
            "contacts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s purges an item'), $_SESSION["glpiname"])
        );
    }
    $contact->redirectToList();
} else if (isset($_POST["update"])) {
    $contact->check($_POST["id"], UPDATE);

    if ($contact->update($_POST)) {
        Event::log(
            $_POST["id"],
            "contacts",
            4,
            "financial",
            //TRANS: %s is the user login
            sprintf(__('%s updates an item'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $menus = ["management", "contact"];
    Contact::displayFullPageForItem($_GET["id"], $menus, [
        'formoptions'  => "data-track-changes=true"
    ]);
}
