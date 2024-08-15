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

Session::checkRight("computer", READ);

Html::header(Computer::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "computer");

Search::show('Computer');

Html::footer();
