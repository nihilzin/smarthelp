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

include('../inc/includes.php');

if (!isset($_GET["id"])) {
    $_GET["id"] = "";
}

if (!isset($_GET["withtemplate"])) {
    $_GET["withtemplate"] = "";
}

$savedsearch = new SavedSearch();
if (isset($_POST["add"])) {
   //Add a new saved search
    $savedsearch->check(-1, CREATE, $_POST);
    if ($savedsearch->add($_POST)) {
        if ($_SESSION['glpibackcreated']) {
            Html::redirect($savedsearch->getLinkURL());
        }
    }
    Html::back();
} else if (isset($_POST["purge"])) {
   // delete a saved search
    $savedsearch->check($_POST['id'], PURGE);
    $savedsearch->delete($_POST, 1);
    $savedsearch->redirectToList();
} else if (isset($_POST["update"])) {
   //update a saved search
    $savedsearch->check($_POST['id'], UPDATE);
    $savedsearch->update($_POST);
    Html::back();
} else if (isset($_GET['create_notif'])) {
    $savedsearch->check($_GET['id'], UPDATE);
    $savedsearch->createNotif();
    Html::back();
} else {
    $menus = [
        'central'  => ['tools', 'savedsearch'],
        'helpdesk' => [],
    ];
    SavedSearch::displayFullPageForItem($_GET["id"], $menus);
}
