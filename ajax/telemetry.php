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

// Must be available during installation. This script already checks for permissions when the flag usually set by the installer is missing.
$SECURITY_STRATEGY = 'no_check';

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

if (!($_SESSION['telemetry_from_install'] ?? false)) {
    Session::checkRight("config", READ);
}

echo Html::css("public/lib/prismjs.css");
echo Html::script("public/lib/prismjs.js");

$infos = Telemetry::getTelemetryInfos();
echo "<p>" . __("We only collect the following data: plugins usage, performance and responsiveness statistics about user interface features, memory, and hardware configuration.") . "</p>";
echo "<pre><code class='language-json'>";
echo json_encode($infos, JSON_PRETTY_PRINT);
echo "</code></pre>";
