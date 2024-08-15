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

Session::checkRight("software", READ);

Html::header(Software::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "software");

Search::show('Software');

Html::footer();
