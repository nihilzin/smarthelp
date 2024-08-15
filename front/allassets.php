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


Session::checkCentralAccess();

Html::header(__('Global'), $_SERVER['PHP_SELF'], "assets", "allassets");

Search::show(AllAssets::getType());


Html::footer();
