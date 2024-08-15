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

Session::checkRight("phone", READ);

Html::header(Phone::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], 'assets', 'phone');

Search::show('Phone');

Html::footer();
