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

Session::checkRight("line", READ);

Html::header(Line::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "management", "line");

Search::show('Line');

Html::footer();
