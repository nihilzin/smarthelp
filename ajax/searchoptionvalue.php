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
$ajax = false;
// Direct access to file
if (strpos($_SERVER['PHP_SELF'], "searchoptionvalue.php")) {
    $ajax = true;
    include('../inc/includes.php');
    header("Content-Type: text/html; charset=UTF-8");
    Html::header_nocache();
} else if (!defined('GLPI_ROOT')) {
    die("Sorry. You can't access this file directly");
}

Session::checkLoginUser();

if (isset($_POST['searchtype'])) {
    $searchopt      = $_POST['searchopt'];
    if ($ajax) {
        $_POST['value'] = rawurldecode($_POST['value']);
    }
    $fieldname = 'criteria';
    if (isset($_POST['meta']) && $_POST['meta']) {
        $fieldname = 'metacriteria';
    }

    $inputname         = $fieldname . '[' . $_POST['num'] . '][value]';
    $display           = false;
    $item              = getItemForItemtype($_POST['itemtype']);
    $options2          = [];
    $options2['value'] = $_POST['value'];
    $options2['width'] = '100%';
   // For tree dropdpowns
    $options2['permit_select_parent'] = true;

    switch ($_POST['searchtype']) {
        case "equals":
        case "notequals":
        case "morethan":
        case "lessthan":
        case "under":
        case "notunder":
            if (!$display && isset($searchopt['field'])) {
                // Specific cases
                switch ($searchopt['table'] . "." . $searchopt['field']) {
                   // Add mygroups choice to searchopt
                    case "glpi_groups.completename":
                        $searchopt['toadd'] = ['mygroups' => __('My groups')];
                        break;

                    case "glpi_users.name":
                        $searchopt['toadd'] = [
                            [
                                'id'    => 'myself',
                                'text'  => __('Myself'),
                            ]
                        ];
                        break;

                    case "glpi_changes.status":
                    case "glpi_changes.impact":
                    case "glpi_changes.urgency":
                    case "glpi_problems.status":
                    case "glpi_problems.impact":
                    case "glpi_problems.urgency":
                    case "glpi_tickets.status":
                    case "glpi_tickets.impact":
                    case "glpi_tickets.urgency":
                        $options2['showtype'] = 'search';
                        break;

                    case "glpi_changes.priority":
                    case "glpi_problems.priority":
                    case "glpi_tickets.priority":
                        $options2['showtype']  = 'search';
                        $options2['withmajor'] = true;
                        break;


                    case "glpi_tickets.global_validation":
                          $options2['all'] = true;
                        break;


                    case "glpi_ticketvalidations.status":
                        $options2['all'] = true;
                        break;


                    case "glpi_users.name":
                        $options2['right']            = ($searchopt['right'] ?? 'all');
                        $options2['inactive_deleted'] = 1;
                        break;
                }

                // Standard datatype usage
                if (!$display && isset($searchopt['datatype'])) {
                    switch ($searchopt['datatype']) {
                        case "date":
                        case "date_delay":
                        case "datetime":
                              $options2['relative_dates'] = true;
                            break;
                    }
                }

                $out = $item->getValueToSelect($searchopt, $inputname, $_POST['value'], $options2);
                if (strlen($out)) {
                    echo $out;
                    $display = true;
                }

               //Could display be handled by a plugin ?
                if (
                    !$display
                    && $plug = isPluginItemType(getItemTypeForTable($searchopt['table']))
                ) {
                    $display = Plugin::doOneHook(
                        $plug['plugin'],
                        'searchOptionsValues',
                        [
                            'name'           => $inputname,
                            'searchtype'     => $_POST['searchtype'],
                            'searchoption'   => $searchopt,
                            'value'          => $_POST['value']
                        ]
                    );
                }
            }
            break;
    }

   // Default case : text field
    if (!$display) {
        echo "<input type='text' size='13' name='$inputname' value=\"" .
               Html::cleanInputText($_POST['value']) . "\">";
    }
}
