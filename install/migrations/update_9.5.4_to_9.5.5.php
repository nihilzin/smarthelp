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
 * Update from 9.5.4 to 9.5.5
 *
 * @return bool for success (will die for most error)
 **/
function update954to955()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult = true;

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '9.5.5'));
    $migration->setVersion('9.5.5');

   /* Add `DEFAULT CURRENT_TIMESTAMP` to some date fields */
    $tables = [
        'glpi_alerts',
        'glpi_crontasklogs',
        'glpi_notimportedemails',
    ];
    foreach ($tables as $table) {
        $type_result = $DB->request(
            [
                'SELECT'       => ['data_type as DATA_TYPE'],
                'FROM'         => 'information_schema.columns',
                'WHERE'       => [
                    'table_schema' => $DB->dbdefault,
                    'table_name'   => $table,
                    'column_name'  => 'date',
                ],
            ]
        );
        $type = $type_result->current()['DATA_TYPE'];
        $migration->changeField($table, 'date', 'date', $type . ' NOT NULL DEFAULT CURRENT_TIMESTAMP');
    }
   /* /Add `DEFAULT CURRENT_TIMESTAMP` to some date fields */

   // ************ Keep it at the end **************
    $migration->executeMigration();

    return $updateresult;
}
