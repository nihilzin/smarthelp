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

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}
if (!isset($_GET["item_itemtype"])) {
    $_GET["item_itemtype"] = "";
}
if (!isset($_GET["item_items_id"])) {
    $_GET["item_items_id"] = "";
}
if (!isset($_GET["modify"])) {
    $_GET["modify"] = "";
}


$kb = new KnowbaseItem();

if (isset($_POST["add"])) {
   // ajoute un item dans la base de connaisssances
    $kb->check(-1, CREATE, $_POST);
    $newID = $kb->add($_POST);
    Event::log(
        $newID,
        "knowbaseitem",
        5,
        "tools",
        sprintf(__('%1$s adds the item %2$s'), $_SESSION["glpiname"], $newID)
    );
    if (isset($_POST['_in_modal']) && $_POST['_in_modal']) {
        Html::redirect($kb->getFormURLWithID($newID) . "&_in_modal=1");
    } else {
        Html::redirect($CFG_GLPI["root_doc"] . "/front/knowbaseitem.php");
    }
} else if (isset($_POST["update"])) {
   // actualiser  un item dans la base de connaissances
    $kb->check($_POST["id"], UPDATE);

    $kb->update($_POST);
    Event::log(
        $_POST["id"],
        "knowbaseitem",
        5,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
    Html::redirect($kb->getFormURLWithID($_POST['id']));
} else if (isset($_POST["purge"])) {
   // effacer un item dans la base de connaissances
    $kb->check($_POST["id"], PURGE);
    $kb->delete($_POST, 1);
    Event::log(
        $_POST["id"],
        "knowbaseitem",
        5,
        "tools",
        //TRANS: %s is the user login
        sprintf(__('%s purges an item'), $_SESSION["glpiname"])
    );
    $kb->redirectToList();
} else if (isset($_POST["addvisibility"])) {
    if (
        isset($_POST["_type"]) && !empty($_POST["_type"])
        && isset($_POST["knowbaseitems_id"]) && $_POST["knowbaseitems_id"]
    ) {
        if (array_key_exists('entities_id', $_POST) && $_POST['entities_id'] == -1) {
            // "No restriction" value selected
            $_POST['entities_id'] = 'NULL';
            $_POST['no_entity_restriction'] = 1;
        }
        $item = null;
        switch ($_POST["_type"]) {
            case 'User':
                if (isset($_POST['users_id']) && $_POST['users_id']) {
                    $item = new KnowbaseItem_User();
                }
                break;

            case 'Group':
                if (isset($_POST['groups_id']) && $_POST['groups_id']) {
                    $item = new Group_KnowbaseItem();
                }
                break;

            case 'Profile':
                if (isset($_POST['profiles_id']) && $_POST['profiles_id']) {
                    $item = new KnowbaseItem_Profile();
                }
                break;

            case 'Entity':
                $item = new Entity_KnowbaseItem();
                break;
        }
        if (!is_null($item)) {
            $item->add($_POST);
            Event::log(
                $_POST["knowbaseitems_id"],
                "knowbaseitem",
                4,
                "tools",
                //TRANS: %s is the user login
                sprintf(__('%s adds a target'), $_SESSION["glpiname"])
            );
        }
    }
    Html::back();
} else if (isset($_GET["id"]) and isset($_GET['to_rev'])) {
    $kb->check($_GET["id"], UPDATE);
    if ($kb->revertTo($_GET['to_rev'])) {
        Session::addMessageAfterRedirect(
            sprintf(
                __('Knowledge base item has been reverted to revision %s'),
                $_GET['to_rev']
            )
        );
    } else {
        Session::addMessageAfterRedirect(
            sprintf(
                __('Knowledge base item has not been reverted to revision %s'),
                $_GET['to_rev']
            ),
            false,
            ERROR
        );
    }
    Html::redirect($kb->getFormURLWithID($_GET['id']));
} else if (isset($_GET["id"])) {
    if (!Session::getLoginUserID()) {
        Html::redirect("helpdesk.faq.php?id=" . $_GET['id']);
    }

    if (isset($_GET["_in_modal"])) {
        Html::popHeader(__('Knowledge base'), $_SERVER['PHP_SELF']);
        if ($_GET['id']) {
            $kb->check($_GET["id"], READ);
            $kb->showFull();
        } else { // New item
            $kb->showForm($_GET["id"], $_GET);
        }
        Html::popFooter();
    } else {
        $available_options = ['item_itemtype', 'item_items_id', 'id'];
        $options           = [];
        foreach ($available_options as $key) {
            if (isset($_GET[$key])) {
                $options[$key] = $_GET[$key];
            }
        }
        $menus = [
            'central'  => ["tools", "knowbaseitem"],
            'helpdesk' => [],
        ];
        KnowbaseItem::displayFullPageForItem($_GET['id'], $menus, $options);
    }
}
