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

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkRight("dropdown", UPDATE);
if (isset($_POST['itemtype']) && isset($_POST['language'])) {
    $item = new $_POST['itemtype']();
    $item->getFromDB($_POST['items_id']);
    DropdownTranslation::dropdownFields($item, $_POST['language']);
}
