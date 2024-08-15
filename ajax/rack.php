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

if (!Session::haveRight('datacenter', UPDATE)) {
    http_response_code(403);
    die;
}
if (!isset($_REQUEST['action'])) {
    exit();
}

$answer = [];
if (($_GET['action'] ?? null) === 'show_pdu_form') {
    PDU_Rack::showFirstForm((int) $_GET['racks_id']);
} else if (isset($_POST['action'])) {
    header("Content-Type: application/json; charset=UTF-8", true);
    switch ($_POST['action']) {
        case 'move_item':
            $item_rack = new Item_Rack();
            $item_rack->getFromDB((int) $_POST['id']);
            $answer['status'] = $item_rack->update([
                'id'       => (int) $_POST['id'],
                'position' => (int) $_POST['position'],
                'hpos'     => (int) $_POST['hpos'],
            ]);
            break;

        case 'move_pdu':
            $pdu_rack = new PDU_Rack();
            $pdu_rack->getFromDB((int) $_POST['id']);
            $answer['status'] = $pdu_rack->update([
                'id'       => (int) $_POST['id'],
                'position' => (int) $_POST['position']
            ]);
            break;

        case 'move_rack':
            $rack = new Rack();
            $rack->getFromDB((int) $_POST['id']);
            $answer['status'] = $rack->update([
                'id'         => (int) $_POST['id'],
                'dcrooms_id' => (int) $_POST['dcrooms_id'],
                'position'   => (int) $_POST['x'] . "," . (int) $_POST['y'],
            ]);
            break;
    }

    echo json_encode($answer);
}
