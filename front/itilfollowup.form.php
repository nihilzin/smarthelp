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

/** @var \DBmysql $DB */
global $DB;

include('../inc/includes.php');

Session::checkLoginUser();

$fup = new ITILFollowup();

$redirect = null;
$handled = false;

if (!isset($_POST['itemtype']) || !class_exists($_POST['itemtype'])) {
    Html::displayErrorAndDie('Lost');
}
$track = new $_POST['itemtype']();


if (isset($_POST["add"])) {
    $fup->check(-1, CREATE, $_POST);
    $fup->add($_POST);

    Event::log(
        $fup->getField('items_id'),
        strtolower($_POST['itemtype']),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s adds a followup'), $_SESSION["glpiname"])
    );
    $redirect = $track->getFormURLWithID($fup->getField('items_id'));
    $handled = true;
} else if (
    isset($_POST['add_close'])
           || isset($_POST['add_reopen'])
) {
    if ($track->getFromDB($_POST['items_id']) && (method_exists($track, 'canApprove') && $track->canApprove())) {
        $fup->add($_POST);

        Event::log(
            $fup->getField('items_id'),
            strtolower($_POST['itemtype']),
            4,
            "tracking",
            //TRANS: %s is the user login
            sprintf(__('%s approves or refuses a solution'), $_SESSION["glpiname"])
        );
    }
} else if (isset($_POST["update"])) {
    $fup->check($_POST['id'], UPDATE);
    $fup->update($_POST);

    Event::log(
        $fup->getField('items_id'),
        strtolower($_POST['itemtype']),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates a followup'), $_SESSION["glpiname"])
    );
    $redirect = $track->getFormURLWithID($fup->getField('items_id'));
    $handled = true;
} else if (isset($_POST["purge"])) {
    $fup->check($_POST['id'], PURGE);
    $fup->delete($_POST, 1);

    Event::log(
        $fup->getField('items_id'),
        strtolower($_POST['itemtype']),
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s purges a followup'), $_SESSION["glpiname"])
    );
    $redirect = $track->getFormURLWithID($fup->getField('items_id'));
}

if ($handled) {
    if (isset($_POST['kb_linked_id'])) {
       //if followup should be linked to selected KB entry
        $params = [
            'knowbaseitems_id' => $_POST['kb_linked_id'],
            'itemtype'         => $track->getType(),
            'items_id'         => $track->getID()
        ];
        $existing = $DB->request(
            'glpi_knowbaseitems_items',
            $params
        );
        if ($existing->numrows() == 0) {
            $kb_item_item = new KnowbaseItem_Item();
            $kb_item_item->add($params);
        }
    }

    if ($track->can($_POST["items_id"], READ)) {
        $toadd = '';
       // Copy followup to KB redirect to KB
        if (isset($_POST['_fup_to_kb']) && $_POST['_fup_to_kb']) {
            $toadd = "&_fup_to_kb=" . $fup->getID();
        }
        $redirect = $track->getLinkURL() . $toadd;
    } else {
        Session::addMessageAfterRedirect(
            __('You have been redirected because you no longer have access to this ticket'),
            true,
            ERROR
        );
        $redirect = $track->getSearchURL();
    }
}

if (null == $redirect) {
    Html::back();
} else {
    Html::redirect($redirect);
}

Html::displayErrorAndDie('Lost');
