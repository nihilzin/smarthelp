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

$solution = new ITILSolution();
$track = new $_POST['itemtype']();
$track->getFromDB($_POST['items_id']);

$redirect = null;
$handled = false;

if (isset($_POST["add"])) {
    $solution->check(-1, CREATE, $_POST);
    if (!$track->canSolve()) {
        Session::addMessageAfterRedirect(
            __('You cannot solve this item!'),
            false,
            ERROR
        );
        Html::back();
    }

    if ($solution->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            $redirect = $track->getLinkURL();
        }
        $handled = true;
    }
} else if (isset($_POST['update'])) {
    $solution->getFromDB($_POST['id']);
    $solution->check($_POST['id'], UPDATE);
    $solution->update($_POST);
    $handled = true;
    $redirect = $track->getLinkURL();

    Event::log(
        $_POST["id"],
        "solution",
        4,
        "tracking",
        //TRANS: %s is the user login
        sprintf(__('%s updates an item'), $_SESSION["glpiname"])
    );
}

if ($handled) {
    if (isset($_POST['kb_linked_id']) && (int) $_POST['kb_linked_id'] > 0) {
       //if solution should be linked to selected KB entry
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
       // Copy solution to KB redirect to KB
        if (isset($_POST['_sol_to_kb']) && $_POST['_sol_to_kb']) {
            $toadd = "&_sol_to_kb=1";
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
