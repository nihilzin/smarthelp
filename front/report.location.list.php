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

use Glpi\Socket;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("reports", READ);

if (isset($_POST["locations_id"]) && $_POST["locations_id"]) {
    Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

    Report::title();

   // Titre
    $name = Dropdown::getDropdownName("glpi_locations", $_POST["locations_id"]);
    echo "<div class='center spaced'><h2>" . sprintf(__('Network report by location: %s'), $name) .
        "</h2></div>";

    $where = [];
    if (!empty($_POST['locations_id'])) {
        $sons = getSonsOf('glpi_locations', $_POST['locations_id']);
        $where = ['glpi_locations.id' => $sons];
    }

    Report::reportForNetworkInformations(
        'glpi_locations', //from
        ['PORT_1' => 'id', 'glpi_networkportethernets' => 'networkports_id'], //joincrit
        $where, //where
        ['glpi_sockets.name AS extra'], //select
        [], //left join
        [
            'glpi_sockets'  => [
                'ON'  => [
                    'glpi_sockets'  => 'locations_id',
                    'glpi_locations'  => 'id'
                ]
            ],
            'glpi_networkportethernets'   => [
                'ON'  => [
                    'glpi_networkportethernets' => 'networkports_id',
                    'glpi_sockets'              => 'networkports_id'
                ]
            ]
        ], //inner join
        ['glpi_locations.completename', 'PORT_1.name'], //order
        Socket::getTypeName()
    );

    Html::footer();
} else {
    Html::redirect($CFG_GLPI['root_doc'] . "/front/report.networking.php");
}
