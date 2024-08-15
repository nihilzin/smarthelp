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

/** @var array $_UPOST */
global $_UPOST;

include('../inc/includes.php');

Session::checkRight("config", READ);

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

$mailgate = new MailCollector();

if (isset($_POST["add"])) {
    $mailgate->check(-1, CREATE, $_POST);

    if (array_key_exists('passwd', $_POST)) {
       // Password must not be altered, it will be encrypted and never displayed, so sanitize is not necessary.
        $_POST['passwd'] = $_UPOST['passwd'];
    }

    if ($newID = $mailgate->add($_POST)) {
        Event::log(
            $newID,
            "mailcollector",
            4,
            "setup",
            sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $_POST["name"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($mailgate->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
    $mailgate->check($_POST['id'], PURGE);
    $mailgate->delete($_POST, 1);

    Event::log(
        $_POST["id"],
        "mailcollector",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $mailgate->redirectToList();
} else if (isset($_POST["update"])) {
    $mailgate->check($_POST['id'], UPDATE);

    if (array_key_exists('passwd', $_POST)) {
       // Password must not be altered, it will be encrypted and never displayed, so sanitize is not necessary.
        $_POST['passwd'] = $_UPOST['passwd'];
    }

    $mailgate->update($_POST);

    Event::log(
        $_POST["id"],
        "mailcollector",
        4,
        "setup",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::back();
} else if (isset($_POST["get_mails"])) {
    $mailgate->check($_POST['id'], UPDATE);
    $mailgate->collect($_POST["id"], 1);

    Html::back();
} else {
    $menus = ["config", "mailcollector"];
    MailCollector::displayFullPageForItem($_GET["id"], $menus);
}
