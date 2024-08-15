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

/**
 * @since 9.2
 */

include('../inc/includes.php');

header('Content-type: application/json');
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['knowbaseitems_id'])) {
    $kbitem = new KnowbaseItem();
    $kbitem->getFromDB(intval($_POST['knowbaseitems_id']));
    $kbitem->showFull();
}
