<?php

/*!
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

$SECURITY_STRATEGY = 'no_check'; // CSS must be accessible also on public pages

if (!defined('GLPI_ROOT')) {
    define('GLPI_ROOT', dirname(__DIR__));
}

use Glpi\Application\ErrorHandler;

$_GET["donotcheckversion"]   = true;
$dont_check_maintenance_mode = true;
$skip_db_check               = true;

//std cache, with DB connection
include_once GLPI_ROOT . "/inc/db.function.php";
include_once GLPI_ROOT . '/inc/config.php';

// Main CSS compilation requires about 140MB of memory on PHP 7.4 (110MB on PHP 8.2).
// Ensure to have enough memory to not reach memory limit.
$max_memory = 192;
if (Toolbox::getMemoryLimit() < ($max_memory * 1024 * 1024)) {
    ini_set('memory_limit', sprintf('%dM', $max_memory));
}

// Ensure warnings will not break CSS output.
ErrorHandler::getInstance()->disableOutput();

$css = Html::compileScss($_GET);

header('Content-Type: text/css');

$is_cacheable = !isset($_GET['debug']) && !isset($_GET['nocache']);
if ($is_cacheable) {
   // Makes CSS cacheable by browsers and proxies
    $max_age = WEEK_TIMESTAMP;
    header_remove('Pragma');
    header('Cache-Control: public');
    header('Cache-Control: max-age=' . $max_age);
    header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', time() + $max_age));
}

echo $css;
