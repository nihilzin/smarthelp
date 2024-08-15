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
if (strpos($_SERVER['PHP_SELF'], "ticketsatisfaction.php")) {
    $AJAX_INCLUDE = 1;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
}

$entity = new Entity();

if (isset($_POST['inquest_config']) && isset($_POST['entities_id'])) {
    if ($entity->getFromDB($_POST['entities_id'])) {
        $inquest_delay     = $entity->getfield('inquest_delay');
        $inquest_rate      = $entity->getfield('inquest_rate');
        $inquest_duration  = $entity->getfield('inquest_duration');
        $max_closedate     = $entity->getfield('max_closedate');
    } else {
        $inquest_delay     = -1;
        $inquest_rate      = -1;
        $inquest_duration  = -1;
        $max_closedate     = '';
    }

    if ($_POST['inquest_config'] > 0) {
        echo "<table class='tab_cadre_fixe w-50'>";
        echo "<tr class='tab_bg_1'><td class='w-50'>" . __('Create survey after') . "</td>";
        echo "<td>";
        Dropdown::showNumber(
            'inquest_delay',
            ['value' => $inquest_delay,
                'min'   => 1,
                'max'   => 90,
                'step'  => 1,
                'toadd' => ['0' => __('As soon as possible')],
                'unit'  => 'day'
            ]
        );
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'>" .
           "<td>" . __('Rate to trigger survey') . "</td>";
        echo "<td>";
        Dropdown::showNumber('inquest_rate', ['value'   => $inquest_rate,
            'min'     => 10,
            'max'     => 100,
            'step'    => 10,
            'toadd'   => [0 => __('Disabled')],
            'unit'    => '%'
        ]);
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td class='w-50'>" . __('Duration of survey') . "</td>";
        echo "<td>";
        Dropdown::showNumber(
            'inquest_duration',
            ['value' => $inquest_duration,
                'min'   => 1,
                'max'   => 180,
                'step'  => 1,
                'toadd' => ['0' => __('Unspecified')],
                'unit'  => 'day'
            ]
        );
        echo "</td></tr>";

        echo "<tr class='tab_bg_1'><td>" . __('For tickets closed after') . "</td><td>";
        Html::showDateTimeField("max_closedate", ['value'      => $max_closedate,
            'timestep'   => 1
        ]);
        echo "</td></tr>";

        if ($_POST['inquest_config'] == 2) {
            echo "<tr class='tab_bg_1'>";
            echo "<td>" . __('Valid tags') . "</td><td>" .
               "[TICKET_ID] [TICKET_NAME] [TICKET_CREATEDATE] [TICKET_SOLVEDATE] " .
               "[REQUESTTYPE_ID] [REQUESTTYPE_NAME] [TICKET_PRIORITY] [TICKET_PRIORITYNAME]  " .
               "[TICKETCATEGORY_ID] [TICKETCATEGORY_NAME] [TICKETTYPE_ID] " .
               "[TICKETTYPE_NAME] [SOLUTIONTYPE_ID] [SOLUTIONTYPE_NAME] " .
               "[SLA_TTO_ID] [SLA_TTO_NAME] [SLA_TTR_ID] [SLA_TTR_NAME] [SLALEVEL_ID] [SLALEVEL_NAME]</td></tr>";

            echo "<tr class='tab_bg_1'><td>" . __('URL') . "</td>";
            echo "<td>";
            echo Html::input('inquest_URL', ['value' => $entity->fields['inquest_URL']]);
            echo "</td></tr>";
        }

        echo "</table>";
    }
}
