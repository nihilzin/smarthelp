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

$AJAX_INCLUDE = 1;

include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['name'])) {
    echo "<textarea " . (isset($_POST['rows']) ? " rows='" . $_POST['rows'] . "' " : "") . " " .
         (isset($_POST['cols']) ? " cols='" . $_POST['cols'] . "' " : "") . "  name='" . $_POST['name'] . "'>";
    echo Html::cleanPostForTextArea(Sanitizer::encodeHtmlSpecialChars(rawurldecode(($_POST["data"]))));
    echo "</textarea>";
}
