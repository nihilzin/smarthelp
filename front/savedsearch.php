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

Session::checkLoginUser();

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(SavedSearch::getTypeName(Session::getPluralNumber()));
} else {
    Html::header(SavedSearch::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], 'tools', 'savedsearch');
}

$savedsearch = new SavedSearch();

if (
    isset($_GET['action']) && $_GET["action"] == "load"
    && isset($_GET["id"]) && ($_GET["id"] > 0)
) {
    $savedsearch->check($_GET["id"], READ);
    $savedsearch->load($_GET["id"]);
    return;
}

Search::show('SavedSearch');
Html::footer();
