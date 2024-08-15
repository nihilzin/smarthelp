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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("reports", READ);

if (isset($_POST["prise"]) && $_POST["prise"]) {
    Html::header(Report::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "report");

    Report::title();

    $name = Dropdown::getDropdownName("glpi_sockets", $_POST["prise"]);

   // Titre
    echo "<div class='center spaced'><h2>" . sprintf(__('Network report by outlet: %s'), $name) .
        "</h2></div>";

    Report::reportForNetworkInformations(
        'glpi_sockets', //from
        ['PORT_1' => 'id', 'glpi_networkportethernets' => 'networkports_id'], //joincrit
        ['glpi_sockets.id' => (int) $_POST["prise"]], //where
        ['glpi_locations.completename AS extra'], //select
        [
            'glpi_locations'  => [
                'ON'  => [
                    'glpi_locations'  => 'id',
                    'glpi_sockets'  => 'locations_id'
                ]
            ]
        ], //left join
        [
            'glpi_networkportethernets'   => [
                'ON'  => [
                    'glpi_networkportethernets' => 'networkports_id',
                    'glpi_sockets'              => 'networkports_id'
                ]
            ]
        ], //inner join
        [], //order
        Location::getTypeName()
    );

    Html::footer();
} else {
    Html::redirect($CFG_GLPI['root_doc'] . "/front/report.networking.php");
}
