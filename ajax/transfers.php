<?php

/**
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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight("transfer", READ);

if (isset($_POST["id"]) && ($_POST["id"] > 0)) {
    $transfer = new Transfer();
    $transfer->showForm(
        $_POST["id"],
        ['target' => $CFG_GLPI["root_doc"] . "/front/transfer.action.php"]
    );
}

Html::ajaxFooter();
