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

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (
    (!isset($_REQUEST['params']['_idor_token']) || empty($_REQUEST['params']['_idor_token'])) || !isset($_REQUEST['itemtype'])
    || !isset($_REQUEST['widget'])
) {
    http_response_code(400);
    die();
}

$idor = $_REQUEST['params']['_idor_token'];
unset($_REQUEST['params']['_idor_token']);

if (
    !Session::validateIDOR([
        'itemtype'     => $_REQUEST['itemtype'],
        '_idor_token'  => $idor
    ] + $_REQUEST['params'])
) {
    http_response_code(400);
    die();
}

$itemtype = $_REQUEST['itemtype'];
$params = $_REQUEST['params'];

switch ($_REQUEST['widget']) {
    case 'central_count':
        if (method_exists($itemtype, 'showCentralCount')) {
            $itemtype::showCentralCount($params['foruser'] ?? false);
        }
        break;
    case 'central_list':
        if (method_exists($itemtype, 'showCentralList')) {
            if (is_subclass_of($itemtype, CommonITILObject::class) || is_subclass_of($itemtype, CommonITILTask::class)) {
                $showgrouptickets = isset($params['showgrouptickets']) ? ($params['showgrouptickets'] !== 'false') : false;
                $itemtype::showCentralList($params['start'], $params['status'] ?? 'process', $showgrouptickets);
            }
        } else if ($itemtype === RSSFeed::class) {
            $personal = $params['personal'] !== 'false';
            $itemtype::showListForCentral($personal);
        } else if ($itemtype === Planning::class) {
            $itemtype::showCentral($params['who']);
        } else if ($itemtype === Reminder::class) {
            $personal = ($params['personal'] ?? true) !== 'false';
            $itemtype::showListForCentral($personal);
        }
        break;
    default:
        echo __('Invalid widget');
}
