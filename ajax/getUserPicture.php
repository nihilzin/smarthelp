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

use Glpi\Http\Response;

$AJAX_INCLUDE = 1;

include('../inc/includes.php');

header("Content-Type: application/json; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (!isset($_REQUEST['users_id'])) {
    Response::sendError(400, "Missing users_id parameter");
} else if (!is_array($_REQUEST['users_id'])) {
    $_REQUEST['users_id'] = [$_REQUEST['users_id']];
}

$_REQUEST['users_id'] = array_unique($_REQUEST['users_id']);

if (!isset($_REQUEST['size'])) {
    $_REQUEST['size'] = '100%';
}

if (!isset($_REQUEST['allow_blank'])) {
    $_REQUEST['allow_blank'] = false;
}

$user = new User();
$imgs = [];

foreach ($_REQUEST['users_id'] as $user_id) {
    if ($user->getFromDB($user_id)) {
        if (!empty($user->fields['picture']) || $_REQUEST['allow_blank']) {
            if (isset($_REQUEST['type']) && $_REQUEST['type'] == 'thumbnail') {
                $path = User::getThumbnailURLForPicture($user->fields['picture']);
            } else {
                $path = User::getURLForPicture($user->fields['picture']);
            }
            $img = Html::image($path, [
                'title'          => getUserName($user_id),
                'data-bs-toggle' => 'tooltip',
                'width'          => $_REQUEST['size'],
                'height'         => $_REQUEST['size'],
                'class'          => $_REQUEST['class'] ?? ''
            ]);
            if (isset($_REQUEST['link']) && $_REQUEST['link']) {
                 $imgs[$user_id] = Html::link($img, User::getFormURLWithID($user_id));
            } else {
                $imgs[$user_id] = $img;
            }
        } else {
           // No picture and default image is not allowed.
            continue;
        }
    }
}

echo json_encode($imgs, JSON_FORCE_OBJECT);
