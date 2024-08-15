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

Session::checkRight("profile", READ);

Html::header(Profile::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "admin", "profile");

Search::show('Profile');

Html::footer();
