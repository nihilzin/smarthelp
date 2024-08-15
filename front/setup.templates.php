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

if (isset($_GET["itemtype"])) {
    $itemtype = $_GET['itemtype'];
    $link     = $itemtype::getFormURL();

   // Get right sector
    $sector   = 'assets';

   //Get sectors from the menu
    $menu     = Html::getMenuInfos();

   //Try to find to which sector the itemtype belongs
    foreach ($menu as $menusector => $infos) {
        if (isset($infos['types']) && in_array($itemtype, $infos['types'])) {
            $sector = $menusector;
            break;
        }
    }

    Html::header(__('Manage templates...'), $_SERVER['PHP_SELF'], $sector, $itemtype);

    CommonDBTM::listTemplates($itemtype, $link, $_GET["add"]);

    Html::footer();
}
