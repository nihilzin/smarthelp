<?php

/**
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

include('../inc/includes.php');
header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

Session::checkLoginUser();

$savedsearch = new SavedSearch();

if (isset($_POST["name"])) {
   //Add a new saved search
    header("Content-Type: application/json; charset=UTF-8");
    $savedsearch->check(-1, CREATE, $_POST);
    if ($savedsearch->add($_POST)) {
        Session::addMessageAfterRedirect(
            __('Search has been saved'),
            false,
            INFO
        );
        echo json_encode(['success' => true]);
    } else {
        Session::addMessageAfterRedirect(
            __('Search has not been saved'),
            false,
            ERROR
        );
        echo json_encode(['success' => false]);
    }
    return;
}

if (
    isset($_GET['mark_default'])
           && isset($_GET["id"])
) {
    $savedsearch->check($_GET["id"], READ);

    if ($_GET["mark_default"] > 0) {
        $savedsearch->markDefault($_GET["id"]);
    } else if ($_GET["mark_default"] == 0) {
        $savedsearch->unmarkDefault($_GET["id"]);
    }
}

if (!isset($_REQUEST['action'])) {
    return;
}

$action = $_REQUEST['action'] ?? null;

if ($action == 'display_mine') {
    header("Content-Type: text/html; charset=UTF-8");
    $savedsearch->displayMine(
        $_GET["itemtype"],
        (bool) ($_GET["inverse"] ?? false),
        false
    );
}

if ($action == 'reorder') {
    $savedsearch->saveOrder($_POST['ids']);
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(['res' => true]);
}

// Create or update a saved search
if ($action == 'create') {
    header("Content-Type: text/html; charset=UTF-8");

    if (!isset($_REQUEST['type'])) {
        $_REQUEST['type'] = -1;
    } else {
        $_REQUEST['type']  = (int)$_REQUEST['type'];
    }

    $id = 0;
    $saved_search = new SavedSearch();

   // If an id was supplied in the query and that the matching saved search
   // is private OR the current user is allowed to edit public searches, then
   // pass the id to showForm
    if (($requested_id = $_REQUEST['id'] ?? 0) > 0 && $saved_search->getFromDB($requested_id)) {
        $is_private = $saved_search->fields['is_private'];
        $can_update_public = Session::haveRight(SavedSearch::$rightname, UPDATE);

        if ($is_private || $can_update_public) {
            $id = $saved_search->getID();
        }
    }

    $savedsearch->showForm(
        $id,
        [
            'type'      => $_REQUEST['type'],
            'url'       => $_REQUEST["url"],
            'itemtype'  => $_REQUEST["itemtype"],
            'ajax'      => true
        ]
    );
    return;
}
