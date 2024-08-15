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

use Glpi\Socket;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();
Session::checkLoginUser();

$action = $_POST['action'] ?? $_GET["action"];

switch ($action) {
    case 'get_items_from_itemtype':
        if ($_POST['itemtype'] && class_exists($_POST['itemtype'])) {
            $_POST['itemtype']::dropdown(['name'                => $_POST['dom_name'],
                'rand'                => $_POST['dom_rand'],
                'display_emptychoice' => true,
                'display_dc_position' => in_array($_POST['itemtype'], $CFG_GLPI['rackable_types']),
                'width'               => '100%',
            ]);
        }
        break;

    case 'get_socket_dropdown':
        if (
            (isset($_GET['itemtype']) && class_exists($_GET['itemtype']))
            && isset($_GET['items_id'])
        ) {
            Socket::dropdown(['name'         =>  $_GET['dom_name'],
                'condition'    => ['socketmodels_id'   => $_GET['socketmodels_id'] ?? 0,
                    'itemtype'           => $_GET['itemtype'],
                    'items_id'           => $_GET['items_id']
                ],
                'used'         => (int)$_GET['items_id'] > 0 ? Socket::getSocketAlreadyLinked($_GET['itemtype'], (int)$_GET['items_id']) : [],
                'displaywith'  => ['itemtype', 'items_id', 'networkports_id'],
            ]);
        }
        break;

    case 'get_networkport_dropdown':
         NetworkPort::dropdown(['name'                => 'networkports_id',
             'display_emptychoice' => true,
             'condition'           => ['items_id' => $_GET['items_id'],
                 'itemtype' => $_GET['itemtype']
             ],
             'comments' => false
         ]);
        break;


    case 'get_item_breadcrum':
        if (
            (isset($_GET['itemtype']) && class_exists($_GET['itemtype']))
            && isset($_GET['items_id']) && $_GET['items_id'] > 0
        ) {
            if (method_exists($_GET['itemtype'], 'getDcBreadcrumbSpecificValueToDisplay')) {
                echo $_GET['itemtype']::getDcBreadcrumbSpecificValueToDisplay($_GET['items_id']);
            }
        } else {
            echo "";
        }
        break;
}
