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

Html::header(SLM::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "config", "slm");

Search::show('SLM');

Html::footer();
