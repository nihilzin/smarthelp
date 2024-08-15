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

// This has to be called before search process is called, in order to add
// "new" plugins in DB to be able to display them.
$plugin = new Plugin();
$plugin->checkStates(true);

Html::header(__('Setup'), $_SERVER['PHP_SELF'], "config", "plugin");

\Glpi\Marketplace\View::showFeatureSwitchDialog();

$catalog_btn = '<div class="center my-2">'
   . '<a href="http://plugins.glpi-project.org" class="btn btn-primary" target="_blank">'
   . "<i class='fas fa-eye'></i>"
   . "<span>" . __('See the catalog of plugins') . "</span>"
   . '</a>'
   . '</div>';

Search::show('Plugin');

echo $catalog_btn;

Html::footer();
