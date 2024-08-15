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

use Glpi\Socket;

include('../inc/includes.php');

Session::checkRight("cable_management", READ);

Html::header(Socket::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "cable", "socket");
Search::show(Socket::class);

Html::footer();
