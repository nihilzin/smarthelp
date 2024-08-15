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

Session::checkRightsOr('change', [Change::READALL, Change::READMY]);

Html::header(Change::getTypeName(Session::getPluralNumber()), '', "helpdesk", "change");

Search::show('Change');

Html::footer();
