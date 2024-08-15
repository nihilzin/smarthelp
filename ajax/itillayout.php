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
use Glpi\Toolbox\Sanitizer;

include('../inc/includes.php');

header('Content-Type: application/json; charset=UTF-8');
Html::header_nocache();

Session::checkLoginUser();

$raw_itillayout  = Sanitizer::unsanitize($_POST['itil_layout']);

$json_itillayout = json_encode($raw_itillayout);
if ($json_itillayout === false) {
    exit;
}

$user = new User();
$success = $user->update(
    [
        'id' => Session::getLoginUserID(),
        'itil_layout' => Sanitizer::dbEscape($json_itillayout),
    ]
);
echo json_encode(['success' => $success]);
