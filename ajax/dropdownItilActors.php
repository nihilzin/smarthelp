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

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkCentralAccess();

// Make a select box
if (
    isset($_POST["type"])
    && isset($_POST["actortype"])
    && isset($_POST["itemtype"])
) {
    $rand = mt_rand();
    $withemail = isset($_POST['allow_email']) && filter_var($_POST['allow_email'], FILTER_VALIDATE_BOOLEAN);

    if ($item = getItemForItemtype($_POST["itemtype"])) {
        switch ($_POST["type"]) {
            case "user":
                $right = 'all';
                // Only steal or own ticket whit empty assign
                if ($_POST["actortype"] == 'assign') {
                    $right = "own_ticket";
                    if (!$item->canAssign()) {
                        $right = 'id';
                    }
                }

                $options = ['name'        => '_itil_' . $_POST["actortype"] . '[users_id]',
                    'entity'      => $_POST['entity_restrict'],
                    'right'       => $right,
                    'rand'        => $rand,
                    'ldap_import' => true
                ];

                if ($CFG_GLPI["notifications_mailing"]) {
                    $paramscomment = ['value' => '__VALUE__',
                        'allow_email' => $withemail,
                        'field' => "_itil_" . $_POST["actortype"],
                        'use_notification' => $_POST["use_notif"]
                    ];
                   // Fix rand value
                    $options['rand'] = $rand;
                    if ($withemail) {
                        $options['toupdate'] = [
                            'value_fieldname' => 'value',
                            'to_update'       => "notif_user_$rand",
                            'url'             => $CFG_GLPI["root_doc"] . "/ajax/uemailUpdate.php",
                            'moreparams'      => $paramscomment
                        ];
                    }
                }

                if (
                    ($_POST["itemtype"] == 'Ticket')
                    && ($_POST["actortype"] == 'assign')
                ) {
                    $toupdate = [];
                    if (isset($options['toupdate']) && is_array($options['toupdate'])) {
                        $toupdate[] = $options['toupdate'];
                    }
                    $toupdate[] = ['value_fieldname' => 'value',
                        'to_update'       => "countassign_$rand",
                        'url'             => $CFG_GLPI["root_doc"] .
                                                            "/ajax/actorinformation.php",
                        'moreparams'      => ['users_id_assign' => '__VALUE__']
                    ];
                    $options['toupdate'] = $toupdate;
                }

                $rand = User::dropdown($options);


                // Display active tickets for a tech
                // Need to update information on dropdown changes
                if (
                    ($_POST["itemtype"] == 'Ticket')
                    && ($_POST["actortype"] == 'assign')
                ) {
                    echo "<br><span id='countassign_$rand'>--";
                    echo "</span>";
                }

                if ($CFG_GLPI["notifications_mailing"]) {
                    echo "<br><span id='notif_user_$rand'>";
                    if ($withemail) {
                        echo __('Email followup') . '&nbsp;';
                        $rand = Dropdown::showYesNo('_itil_' . $_POST["actortype"] . '[use_notification]', $_POST["use_notif"]);
                        echo '<br>';
                        printf(
                            __('%1$s: %2$s'),
                            _n('Email', 'Emails', 1),
                            "<input type='text' size='25' name='_itil_" . $_POST["actortype"] .
                            "[alternative_email]'>"
                        );
                    }
                    echo "</span>";
                }
                break;

            case "group":
                $cond = ['is_requester' => 1];
                if ($_POST["actortype"] == 'assign') {
                    $cond = ['is_assign' => 1];
                }
                if ($_POST["actortype"] == 'observer') {
                    $cond = ['is_watcher' => 1];
                }

                $param = [
                    'name'      => '_itil_' . $_POST["actortype"] . '[groups_id]',
                    'entity'    => $_POST['entity_restrict'],
                    'condition' => $cond,
                    'rand'      => $rand
                ];
                if (
                    ($_POST["itemtype"] == 'Ticket')
                    && ($_POST["actortype"] == 'assign')
                ) {
                    $param['toupdate'] = ['value_fieldname' => 'value',
                        'to_update'       => "countgroupassign_$rand",
                        'url'             => $CFG_GLPI["root_doc"] .
                                                                  "/ajax/actorinformation.php",
                        'moreparams'      => ['groups_id_assign'
                                                                        => '__VALUE__'
                        ]
                    ];
                }

                $rand = Group::dropdown($param);

                if (
                    ($_POST["itemtype"] == 'Ticket')
                    && ($_POST["actortype"] == 'assign')
                ) {
                    echo "<br><span id='countgroupassign_$rand'>";
                    echo "</span>";
                }

                break;

            case "supplier":
                $options = ['name'      => '_itil_' . $_POST["actortype"] . '[suppliers_id]',
                    'entity'    => $_POST['entity_restrict'],
                    'rand'      => $rand
                ];
                if ($CFG_GLPI["notifications_mailing"]) {
                    $paramscomment = ['value'       => '__VALUE__',
                        'allow_email' => $withemail,
                        'field'       => '_itil_' . $_POST["actortype"],
                        'typefield'   => "supplier",
                        'use_notification' => $_POST["use_notif"]
                    ];
                   // Fix rand value
                    $options['rand']     = $rand;
                    if ($withemail) {
                        $options['toupdate'] = [
                            'value_fieldname' => 'value',
                            'to_update'       => "notif_supplier_$rand",
                            'url'             => $CFG_GLPI["root_doc"] . "/ajax/uemailUpdate.php",
                            'moreparams'      => $paramscomment
                        ];
                    }
                }
                if ($_POST["itemtype"] == 'Ticket') {
                    $toupdate = [];
                    if (isset($options['toupdate']) && is_array($options['toupdate'])) {
                        $toupdate[] = $options['toupdate'];
                    }
                    $toupdate[] = ['value_fieldname' => 'value',
                        'to_update'       => "countassign_$rand",
                        'url'             => $CFG_GLPI["root_doc"] .
                                                            "/ajax/actorinformation.php",
                        'moreparams'      => ['suppliers_id_assign' => '__VALUE__']
                    ];
                    $options['toupdate'] = $toupdate;
                }

                $rand = Supplier::dropdown($options);
               // Display active tickets for a supplier
               // Need to update information on dropdown changes
                if ($_POST["itemtype"] == 'Ticket') {
                    echo "<span id='countassign_$rand'>";
                    echo "</span>";
                }
                if ($CFG_GLPI["notifications_mailing"]) {
                    echo "<br><span id='notif_supplier_$rand'>";
                    if ($withemail) {
                        echo __('Email followup') . '&nbsp;';
                        $rand = Dropdown::showYesNo('_itil_' . $_POST["actortype"] . '[use_notification]', $_POST['use_notif']);
                        echo '<br>';
                        printf(
                            __('%1$s: %2$s'),
                            _n('Email', 'Emails', 1),
                            "<input type='text' size='25' name='_itil_" . $_POST["actortype"] .
                            "[alternative_email]'>"
                        );
                    }
                    echo "</span>";
                }
                break;
        }
    }
}
