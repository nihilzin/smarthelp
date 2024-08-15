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

Session::checkRight("config", UPDATE);

Html::header(AuthMail::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "config", "auth", "imap");

Search::show('AuthMail');

Html::footer();
