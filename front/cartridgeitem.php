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

Session::checkRight("cartridge", READ);

Html::header(Cartridge::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "cartridgeitem");

Search::show('CartridgeItem');

Html::footer();
