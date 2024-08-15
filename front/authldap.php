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

Session::checkRight("config", READ);

Html::header(AuthLDAP::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], 'config', 'auth', 'ldap');

Search::show('AuthLDAP');

Html::footer();
