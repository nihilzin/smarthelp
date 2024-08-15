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

Session::checkRight("slm", READ);

Html::header(OLA::getTypeName(1), $_SERVER['PHP_SELF'], "config", "slm", "ola");

Search::show('OLA');

Html::footer();
