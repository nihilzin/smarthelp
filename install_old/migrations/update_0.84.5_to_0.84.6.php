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
 * Update from 0.84.5 to 0.84.6
 *
 * @return bool for success (will die for most error)
 **/
function update0845to0846()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '0.84.6'));
    $migration->setVersion('0.84.6');

    $backup_tables = false;
    $newtables     = [];

    foreach ($newtables as $new_table) {
       // rename new tables if exists ?
        if ($DB->tableExists($new_table)) {
            $migration->dropTable("backup_$new_table");
            $migration->displayWarning("$new_table table already exists. " .
                                    "A backup have been done to backup_$new_table.");
            $backup_tables = true;
            $query         = $migration->renameTable("$new_table", "backup_$new_table");
        }
    }
    if ($backup_tables) {
        $migration->displayWarning(
            "You can delete backup tables if you have no need of them.",
            true
        );
    }

   // correct entities_id in documents_items
    $query_doc_i = "UPDATE `glpi_documents_items` as `doc_i`
                   INNER JOIN `glpi_documents` as `doc`
                     ON  `doc`.`id` = `doc_i`.`documents_id`
                   SET `doc_i`.`entities_id` = `doc`.`entities_id`,
                       `doc_i`.`is_recursive` = `doc`.`is_recursive`";
    $DB->doQueryOrDie($query_doc_i, "0.84.6 change entities_id in documents_items");

    $status  = ['new'           => CommonITILObject::INCOMING,
        'assign'        => CommonITILObject::ASSIGNED,
        'plan'          => CommonITILObject::PLANNED,
        'waiting'       => CommonITILObject::WAITING,
        'solved'        => CommonITILObject::SOLVED,
        'closed'        => CommonITILObject::CLOSED,
        'accepted'      => CommonITILObject::ACCEPTED,
        'observe'       => CommonITILObject::OBSERVED,
        'evaluation'    => CommonITILObject::EVALUATION,
        'approbation'   => CommonITILObject::APPROVAL,
        'test'          => CommonITILObject::TEST,
        'qualification' => CommonITILObject::QUALIFICATION
    ];
   // Migrate datas
    foreach ($status as $old => $new) {
        $query = "UPDATE `glpi_tickettemplatepredefinedfields`
                SET `value` = '$new'
                WHERE `value` = '$old'
                      AND `num` = 12";
        $DB->doQueryOrDie($query, "0.84.6 status in glpi_tickettemplatepredefinedfields $old to $new");
    }
    foreach (['glpi_ipaddresses', 'glpi_networknames'] as $table) {
        $migration->dropKey($table, 'item');
        $migration->migrationOneTable($table);
        $migration->addKey($table, ['itemtype', 'items_id', 'is_deleted'], 'item');
    }

   // must always be at the end
    $migration->executeMigration();

    return $updateresult;
}
