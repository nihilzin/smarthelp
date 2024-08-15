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

// Direct access to file
include('../inc/includes.php');
header("Content-Type: application/json; charset=UTF-8");

Session::checkLoginUser();

// Tech only
if (Session::getCurrentInterface() !== "central") {
    http_response_code(403);
    die;
}

// Read parameter and load pending reason
$pending_reason = PendingReason::getById($_REQUEST['pendingreasons_id'] ?? null);
if (!$pending_reason) {
    http_response_code(400);
    die;
}

echo json_encode([
    'followup_frequency'          => $pending_reason->fields['followup_frequency'],
    'followups_before_resolution' => $pending_reason->fields['followups_before_resolution'],
]);
