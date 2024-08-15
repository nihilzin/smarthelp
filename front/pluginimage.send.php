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

use Glpi\Event;

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

if (!isset($_GET["name"]) || !isset($_GET["plugin"]) || !Plugin::isPluginActive($_GET["plugin"])) {
    Event::log(
        0,
        "system",
        2,
        "security",
        //TRANS: %s is user name
        sprintf(__('%s makes a bad usage.'), $_SESSION["glpiname"])
    );
    die("security");
}

$dir = GLPI_PLUGIN_DOC_DIR . "/" . $_GET["plugin"] . "/";
if (isset($_GET["folder"])) {
    $dir .= $_GET["folder"] . "/";
}
$filepath = $dir . $_GET["name"];

if (
    (basename($_GET["name"]) != $_GET["name"])
    || (basename($_GET["plugin"]) != $_GET["plugin"])
    || !str_starts_with(realpath($filepath), realpath(GLPI_PLUGIN_DOC_DIR))
    || !Document::isImage($filepath)
) {
    Event::log(
        0,
        "system",
        1,
        "security",
        sprintf(__('%s tries to use a non standard path.'), $_SESSION["glpiname"])
    );
    die("security");
}

// Now send the file with header() magic
header("Expires: Sun, 30 Jan 1966 06:30:00 GMT");
header('Pragma: private'); /// IE BUG + SSL
header('Cache-control: private, must-revalidate'); /// IE BUG + SSL
header('Content-disposition: filename="' . $_GET["name"] . '"');

if (file_exists($filepath)) {
    header("Content-type: " . Toolbox::getMime($filepath));
    readfile($filepath);
} else {
    header("Content-type: image/png");
    readfile($CFG_GLPI['root_doc'] . "/pics/warning.png");
}
