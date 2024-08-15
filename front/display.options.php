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

if (!defined('GLPI_ROOT')) {
    include('../inc/includes.php');
}

Html::popHeader(__('Display options'), $_SERVER['PHP_SELF']);

if (!isset($_GET['itemtype'])) {
    Html::displayErrorAndDie("lost");
}
$itemtype = $_GET['itemtype'];
if (!isset($_GET["sub_itemtype"])) {
    $_GET["sub_itemtype"] = '';
}

if ($item = getItemForItemtype($itemtype)) {
    if (isset($_GET['update']) || isset($_GET['reset'])) {
        $item->updateDisplayOptions($_GET, $_GET["sub_itemtype"]);
    }
    $item->checkGlobal(READ);
    $item->showDislayOptions($_GET["sub_itemtype"]);
}

Html::popFooter();
