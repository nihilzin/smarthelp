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
Html::header_nocache();

Session::checkLoginUser();

if ($_SESSION['glpi_use_mode'] !== Session::DEBUG_MODE) {
    http_response_code(403);
    die();
}

\Glpi\Debug\Profiler::getInstance()->disable();

if (isset($_GET['ajax_id'])) {
    // Get debug data for a specific ajax call
    $ajax_id = $_GET['ajax_id'];
    $profile = \Glpi\Debug\Profile::pull($ajax_id);

    // Close session ASAP to not block other requests.
    // DO NOT do it before call to `\Glpi\Debug\Profile::pull()`,
    // as we have to delete profile from `$_SESSION` during the pull operation.
    session_write_close();

    if ($profile) {
        $data = $profile->getDebugInfo();
        if ($data) {
            header('Content-Type: application/json');
            echo json_encode($data);
            die();
        }
    }
    http_response_code(404);
    die();
}

http_response_code(400);
die();
