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

Session::checkRight("unmanaged", READ);

Html::header(Unmanaged::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "unmanaged");

Search::show('Unmanaged');

Html::footer();
