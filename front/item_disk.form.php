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

Session::checkCentralAccess();

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["items_id"])) {
    $_GET["items_id"] = "";
}
if (!isset($_GET["itemtype"])) {
    $_GET['itemtype'] = '';
}

$disk = new Item_Disk();
if (isset($_POST["add"])) {
    $disk->check(-1, CREATE, $_POST);

    if ($disk->add($_POST)) {
        Event::log(
            $_POST['items_id'],
            $_POST['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s adds a volume'), $_SESSION["glpiname"])
        );
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($disk->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["delete"])) {
    $disk->check($_POST["id"], DELETE);
    $disk->delete($_POST);

    Event::log(
        $_POST["id"],
        $_POST['itemtype'],
        4,
        "inventory",
        //TRANS: %s is the user login
        sprintf(__('%s deletes an item'), $_SESSION["glpiname"])
    );
    $disk->redirectToList();
} else if (isset($_POST["purge"])) {
    $disk->check($_POST["id"], PURGE);

    if ($disk->delete($_POST, 1)) {
        Event::log(
            $disk->fields['items_id'],
            $disk->fields['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s purges a volume'), $_SESSION["glpiname"])
        );
    }
    $itemtype = $disk->fields['itemtype'];
    $item = new $itemtype();
    $item->getFromDB($disk->fields['items_id']);
    Html::redirect($itemtype::getFormURLWithID($disk->fields['items_id']) .
                  ($item->fields['is_template'] ? "&withtemplate=1" : ""));
} else if (isset($_POST["update"])) {
    $disk->check($_POST["id"], UPDATE);

    if ($disk->update($_POST)) {
        Event::log(
            $disk->fields['items_id'],
            $disk->fields['itemtype'],
            4,
            "inventory",
            //TRANS: %s is the user login
            sprintf(__('%s updates a volume'), $_SESSION["glpiname"])
        );
    }
    Html::back();
} else {
    $itemtype = "computer";
    if ($_GET['id'] != '') {
        $disk->getFromDB($_GET['id']);
    }
    if (!$disk->isNewItem()) {
        $itemtype = $disk->fields['itemtype'];
    } else if ($_GET['itemtype'] != '') {
        $itemtype = $_GET['itemtype'];
    }
    $menus = ["assets", $itemtype];
    Item_Disk::displayFullPageForItem($_GET["id"], $menus, [
        'items_id'  => $_GET["items_id"],
        'itemtype'  => $_GET['itemtype']
    ]);
}
