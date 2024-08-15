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

Session::checkRightsOr('reservation', [READ, ReservationItem::RESERVEANITEM]);

if (!isset($_REQUEST["action"])) {
    exit;
}

if ($_REQUEST["action"] == "get_events") {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(Reservation::getEvents($_REQUEST));
    exit;
}

Session::checkRight('reservation', ReservationItem::RESERVEANITEM);

if ($_REQUEST["action"] == "get_resources") {
    header("Content-Type: application/json; charset=UTF-8");
    echo json_encode(Reservation::getResources());
    exit;
}

if (($_POST['action'] ?? null) === "update_event") {
    $result = Reservation::updateEvent($_REQUEST);
    echo json_encode(['result' => $result]);
    exit;
}

Html::header_nocache();
header("Content-Type: text/html; charset=UTF-8");

if ($_REQUEST["action"] == "add_reservation_fromselect") {
    $reservation = new Reservation();
    $reservation->showForm(0, [
        'item'  => [(int) $_REQUEST['id']],
        'begin' => $_REQUEST['start'],
        'end'   => $_REQUEST['end'],
    ]);
}

Html::ajaxFooter();
