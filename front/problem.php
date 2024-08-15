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

Session::checkRightsOr('problem', [Problem::READALL, Problem::READMY]);

Html::header(Problem::getTypeName(Session::getPluralNumber()), '', "helpdesk", "problem");

Search::show('Problem');

Html::footer();
