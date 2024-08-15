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

Session::checkLoginUser();
$item_ticket = new Item_Ticket();

switch ($_GET['action']) {
    case 'add':
        if (isset($_GET['my_items']) && !empty($_GET['my_items'])) {
            list($_GET['itemtype'], $_GET['items_id']) = explode('_', $_GET['my_items']);
        }
        if (isset($_GET['items_id']) && isset($_GET['itemtype']) && !empty($_GET['items_id'])) {
            $_GET['params']['items_id'][$_GET['itemtype']][$_GET['items_id']] = $_GET['items_id'];
        }
        Item_Ticket::itemAddForm(new Ticket(), $_GET['params']);
        break;

    case 'delete':
        if (isset($_GET['items_id']) && isset($_GET['itemtype']) && !empty($_GET['items_id'])) {
            $deleted = true;
            if ($_GET['params']['id'] > 0) {
                $deleted = $item_ticket->deleteByCriteria(['tickets_id' => $_GET['params']['id'],
                    'items_id'   => $_GET['items_id'],
                    'itemtype'   => $_GET['itemtype']
                ]);
            }
            if ($deleted) {
                unset($_GET['params']['items_id'][$_GET['itemtype']][array_search($_GET['items_id'], $_GET['params']['items_id'][$_GET['itemtype']])]);
            }
            Item_Ticket::itemAddForm(new Ticket(), $_GET['params']);
        }

        break;
}
