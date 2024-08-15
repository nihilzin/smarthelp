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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['action'])) {
    $item_rack = new Item_Rack();
    $item_rack->getFromDB((int) $_POST['id']);
    $answer    = [];

    switch ($_POST['action']) {
        case 'move_item':
            $answer['status'] = $item_rack->update([
                'id'       => (int) $_POST['id'],
                'position' => (int) $_POST['position'],
                'hpos'     => (int) $_POST['hpos'],
            ]);
            break;
    }

    echo json_encode($answer);
}
