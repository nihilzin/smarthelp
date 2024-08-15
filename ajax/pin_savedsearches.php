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

header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

Session::checkLoginUser();

if (!is_string($_POST['itemtype']) || getItemForItemtype($_POST['itemtype']) === false) {
    echo json_encode(['success' => false]);
    exit();
}

$all_pinned = importArrayFromDB($_SESSION['glpisavedsearches_pinned']);
$already_pinned = $all_pinned[$_POST['itemtype']] ?? 0;
$all_pinned[$_POST['itemtype']] = $already_pinned ? 0 : 1;
$_SESSION['glpisavedsearches_pinned'] = exportArrayToDB($all_pinned);

$user = new User();
$success = $user->update(
    [
        'id'                   => Session::getLoginUserID(),
        'savedsearches_pinned' => $_SESSION['glpisavedsearches_pinned'],
    ]
);

echo json_encode(['success' => $success]);
