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

$AJAX_INCLUDE = 1;
include('../inc/includes.php');

// Send UTF8 Headers
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST['type']) && isset($_POST['end'])) {
    echo "<table style='width: 90%'>";
    switch ($_POST['type']) {
        case 'day':
            echo "<tr><td>" . __('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $_POST['end']]);
            echo "</td></tr>";
            break;

        case 'week':
            echo "<tr><td>" . __('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $_POST['end']]);
            echo "</td></tr></table>";
            echo "<table class='tab_glpi'>";
            echo "<tr class='center'><td>&nbsp;</td>";
            $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            foreach ($days as $day) {
                echo "<th>" . __($day) . "</th>";
            }
            echo "</tr><tr class='center'><td>" . __('By day') . '</td>';

            foreach ($days as $day) {
                echo "<td><input type='checkbox' name='periodicity[days][$day]'></td>";
            }
            echo "</tr>";
            break;

        case 'month':
            echo "<tr><td colspan='2'>";
            $values = ['date' => __('Each month, same date'),
                'day'  => __('Each month, same day of week')
            ];
            Dropdown::showFromArray('periodicity[subtype]', $values);
            echo "</td></tr>";
            echo "<tr><td>" . __('End date') . '</td><td>';
            Html::showDateField('periodicity[end]', ['value' => $_POST['end']]);
            echo "</td></tr>";
    }
    echo '</table>';
}
