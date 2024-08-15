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
 * Update from 9.5.6 to 9.5.7
 *
 * @return bool for success (will die for most error)
 **/
function update956to957()
{
    /**
     * @var array $CFG_GLPI
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration, $CFG_GLPI;

    $current_config   = Config::getConfigurationValues('core');
    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.5.7'));
    $migration->setVersion('9.5.7');

   /* Fix null `date` in ITIL tables */
    $itil_tables = ['glpi_changes', 'glpi_problems', 'glpi_tickets'];
    foreach ($itil_tables as $itil_table) {
        $migration->addPostQuery(
            $DB->buildUpdate(
                $itil_table,
                ['date' => new QueryExpression($DB->quoteName($itil_table . '.date_creation'))],
                ['date' => null]
            )
        );
    }
   /* /Fix null `date` in ITIL tables */

    /** Replace -1 values for glpi_events.items_id field */
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_events',
            ['items_id' => '0'],
            ['items_id' => '-1', 'type' => 'system']
        )
    );
    /** /Replace -1 values for glpi_events.items_id field */

    /** Replace -1 values for glpi_networkportaliases.networkports_id_alias field */
    $migration->addPostQuery(
        $DB->buildUpdate(
            'glpi_networkportaliases',
            ['networkports_id_alias' => '0'],
            ['networkports_id_alias' => '-1']
        )
    );
    /** /Replace -1 values for glpi_networkportaliases.networkports_id_alias field */

    /** Fix unicity in glpi_items_operatingsystems table */
    // Some items have linked to both '0' and '-1' `operatingsystems_id`
    // In this case, updating '-1' to '0' will fail due to unicity key.
    // First, find item that contains duplicated data
    $data = $DB->request([
        'SELECT' => [
            'MAX' => 'id as valid_entry_id',
            'items_id',
            'itemtype',
            'operatingsystemarchitectures_id',
        ],
        'FROM' => 'glpi_items_operatingsystems',
        'WHERE' => [
            'operatingsystems_id' => [-1, 0]
        ],
        'GROUPBY' => [
            'items_id',
            'itemtype',
            'operatingsystemarchitectures_id',
        ],
        'HAVING' => [new QueryExpression("COUNT(*) > 1")]
    ]);

    // Keep only the latest value for each items with duplicated data
    foreach ($data as $row) {
        $delete = $DB->buildDelete('glpi_items_operatingsystems', [
            'operatingsystems_id' => [-1, 0],
            'itemtype' => $row['itemtype'],
            'items_id' => $row['items_id'],
            'operatingsystemarchitectures_id' => $row['operatingsystemarchitectures_id'],
            'id' => ['!=', $row['valid_entry_id']],
        ]);
        $migration->addPostQuery($delete);
    }
    /** /Fix unicity in glpi_items_operatingsystems table */

    /** Replace -1 values for glpi_items_operatingsystems table foreign key fields */
    foreach (['operatingsystems_id', 'operatingsystemversions_id', 'operatingsystemservicepacks_id'] as $item_os_fkey) {
        $migration->addPostQuery(
            $DB->buildUpdate(
                'glpi_items_operatingsystems',
                [$item_os_fkey => '0'],
                [$item_os_fkey => '-1']
            )
        );
    }
    /** /Replace -1 values for glpi_items_operatingsystems table foreign key fields */

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
