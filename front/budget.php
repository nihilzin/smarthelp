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

Session::checkRight("budget", READ);

Html::header(Budget::getTypeName(1), $_SERVER['PHP_SELF'], "management", "budget");

Search::show('Budget');

Html::footer();
