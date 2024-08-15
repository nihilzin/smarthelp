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

use Glpi\Inventory\Conf;

include('../inc/includes.php');

Session::checkRight(Conf::$rightname, Conf::IMPORTFROMFILE);

Html::header(__('Inventory'), $_SERVER['PHP_SELF'], "admin", "glpi\inventory\inventory");

$conf = new Conf();

if (isset($_FILES['inventory_files'])) {
    $conf->displayImportFiles($_FILES);
} elseif (isset($_POST['update'])) {
    unset($_POST['update']);
    $conf->saveConf($_POST);
    Session::addMessageAfterRedirect(
        __('Configuration has been updated'),
        false,
        INFO
    );
    Html::back();
} else {
    $conf->display(['id' => 1]);
}

Html::footer();
