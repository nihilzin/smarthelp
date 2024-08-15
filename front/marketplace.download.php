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

include("../inc/includes.php");

Session::checkRight("config", UPDATE);

use Glpi\Marketplace\Controller as MarketplaceController;

if (isset($_REQUEST['key'])) {
    $marketplace_ctrl = new MarketplaceController($_REQUEST['key']);
    $marketplace_ctrl->proxifyPluginArchive();
}
