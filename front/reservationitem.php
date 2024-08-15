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

Session::checkRightsOr('reservation', [READ, ReservationItem::RESERVEANITEM]);

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpHeader(__('Simplified interface'), 'reservation');
} else {
    Html::header(Reservation::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "reservationitem");
}

$res = new ReservationItem();
$res->display($_GET);

if (isset($_POST['submit'])) {
    $_SESSION['glpi_saved']['ReservationItem'] = $_POST;
} else {
    unset($_SESSION['glpi_saved']['ReservationItem']);
}

if (Session::getCurrentInterface() == "helpdesk") {
    Html::helpFooter();
} else {
    Html::footer();
}
