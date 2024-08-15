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

Session::checkRight("consumable", READ);

Html::header(Consumable::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "assets", "consumableitem");

if (isset($_GET["synthese"])) {
    Consumable::showSummary();
} else {
    Search::show('ConsumableItem');
}

Html::footer();
