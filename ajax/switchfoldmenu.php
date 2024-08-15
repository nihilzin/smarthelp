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

$user = new User();
$success = $user->update(
    [
        'id'        => Session::getLoginUserID(),
        'fold_menu' => (bool)$_SESSION['glpifold_menu'] ? 0 : 1,
    ]
);

echo json_encode(['success' => $success]);
