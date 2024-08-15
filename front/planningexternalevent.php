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

Session::checkRight("planning", READ);

Html::header(
    PlanningExternalEvent::getTypeName(Session::getPluralNumber()),
    $_SERVER['PHP_SELF'],
    "helpdesk",
    "planning",
    "external"
);

Search::show('PlanningExternalEvent');

Html::footer();
