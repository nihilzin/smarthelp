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
    echo "<input type='text' " . (isset($_POST["size"]) ? " size='" . $_POST["size"] . "' " : "") . " " .
         (isset($_POST["maxlength"]) ? "maxlength='" . $_POST["maxlength"] . "' " : "") . " name='" .
         $_POST['name'] . "' value=\"" .
         Html::cleanInputText(Sanitizer::encodeHtmlSpecialChars(rawurldecode(stripslashes($_POST["data"])))) .
        "\">";
}
