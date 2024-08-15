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

if (strpos($_SERVER['PHP_SELF'], "private_public.php")) {
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

if (isset($_POST['is_private'])) {
    Session::checkLoginUser();

    switch ($_POST['is_private']) {
        case true:
            echo "<input type='hidden' name='is_private' value='1'>\n";
            echo "<input type='hidden' name='entities_id' value='0'>\n";
            echo "<input type='hidden' name='is_recursive' value='0'>\n";
            $private =  __('Personal');
            $link    = "<a href='#' onClick='setPublic" . $_POST['rand'] . "();return false;'>" . __('Set public') . "</a>";
            printf(__('%1$s - %2$s'), $private, $link);
            break;

        case false:
            if (
                isset($_POST['entities_id'])
                && in_array($_POST['entities_id'], $_SESSION['glpiactiveentities'])
            ) {
                $val = $_POST['entities_id'];
            } else {
                $val = $_SESSION['glpiactive_entity'];
            }
            echo "<table class='w-100'>";
            echo "<tr><td>";
            echo "<input type='hidden' name='is_private' value='0'>\n";
            echo __('Public');
            echo "</td><td>";
            Entity::dropdown(['value' => $val]);
            echo "</td><td>" . __('Child entities') . "</td><td>";
            Dropdown::showYesNo('is_recursive', $_POST["is_recursive"]);
            echo "</td><td>";
            echo "<a href='#' onClick='setPrivate" . $_POST['rand'] . "();return false'>" . __('Set personal') . "</a>";
            echo "</td></tr></table>";
            break;
    }
}
