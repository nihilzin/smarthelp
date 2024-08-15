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

/**
 * Update from 9.5.2 to 9.5.3
 *
 * @return bool for success (will die for most error)
 **/
function update952to953()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.5.3'));
    $migration->setVersion('9.5.3');

   /* Fix rule criteria names */
    $mapping = [
        'RuleMailCollector' => [
            'GROUPS' => '_groups_id_requester'
        ],
        'RuleRight' => [
            'GROUPS' => '_groups_id',
        ],
        'RuleTicket' => [
            'users_locations' => '_locations_id_of_requester',
            'items_locations' => '_locations_id_of_item',
            'items_groups'    => '_groups_id_of_item',
            'items_states'    => '_states_id_of_item',
        ]
    ];
    foreach ($mapping as $type => $names) {
        foreach ($names as $oldname => $newname) {
            $migration->addPostQuery(
                $DB->buildUpdate(
                    'glpi_rulecriterias',
                    ['criteria' => $newname],
                    ['glpi_rulecriterias.criteria' => $oldname, 'glpi_rules.sub_type' => $type],
                    [
                        'LEFT JOIN' => [
                            'glpi_rules' => [
                                'FKEY' => [
                                    'glpi_rulecriterias' => 'rules_id',
                                    'glpi_rules'         => 'id'
                                ],
                            ],
                        ],
                    ]
                )
            );
        }
    }
   /* /Fix rule criteria names */

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
