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
 * Update from 0.84.3 to 0.84.4
 *
 * @return bool for success (will die for most error)
 **/
function update0843to0844()
{
    /**
     * @var \DBmysql $DB
     * @var \Migration $migration
     */
    global $DB, $migration;

    $updateresult     = true;
    $ADDTODISPLAYPREF = [];

   //TRANS: %s is the number of new version
    $migration->displayTitle(sprintf(__('Update to %s'), '0.84.4'));
    $migration->setVersion('0.84.4');

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

   // Upgrade ticket bookmarks and clean _glpi_csrf_token
    $status =  ['new'           => CommonITILObject::INCOMING,
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

   // Migrate templates : back for validation
    $query = "SELECT `glpi_notificationtemplatetranslations`.*
               FROM `glpi_notificationtemplatetranslations`
               INNER JOIN `glpi_notificationtemplates`
                  ON (`glpi_notificationtemplates`.`id`
                        = `glpi_notificationtemplatetranslations`.`notificationtemplates_id`)
               WHERE `glpi_notificationtemplatetranslations`.`content_text` LIKE '%validation.storestatus=%'
                     OR `glpi_notificationtemplatetranslations`.`content_html` LIKE '%validation.storestatus=%'
                     OR `glpi_notificationtemplatetranslations`.`subject` LIKE '%validation.storestatus=%'";

    if ($result = $DB->doQuery($query)) {
        if ($DB->numrows($result)) {
            while ($data = $DB->fetchAssoc($result)) {
                $subject = $data['subject'];
                $text = $data['content_text'];
                $html = $data['content_html'];
                foreach ($status as $old => $new) {
                    $subject = str_replace("validation.storestatus=$new", "validation.storestatus=$old", $subject);
                    $text    = str_replace("validation.storestatus=$new", "validation.storestatus=$old", $text);
                    $html    = str_replace("validation.storestatus=$new", "validation.storestatus=$old", $html);
                }
                $query = "UPDATE `glpi_notificationtemplatetranslations`
                        SET `subject` = '" . addslashes($subject) . "',
                           `content_text` = '" . addslashes($text) . "',
                           `content_html` = '" . addslashes($html) . "'
                        WHERE `id` = " . $data['id'] . "";
                $DB->doQueryOrDie($query, "0.84.4 fix tags usage for storestatus");
            }
        }
    }

   // ************ Keep it at the end **************
   //TRANS: %s is the table or item to migrate
    $migration->displayMessage(sprintf(__('Data migration - %s'), 'glpi_displaypreferences'));

    foreach ($ADDTODISPLAYPREF as $type => $tab) {
        $query = "SELECT DISTINCT `users_id`
                FROM `glpi_displaypreferences`
                WHERE `itemtype` = '$type'";

        if ($result = $DB->doQuery($query)) {
            if ($DB->numrows($result) > 0) {
                while ($data = $DB->fetchAssoc($result)) {
                    $query = "SELECT MAX(`rank`)
                         FROM `glpi_displaypreferences`
                         WHERE `users_id` = '" . $data['users_id'] . "'
                               AND `itemtype` = '$type'";
                    $result = $DB->doQuery($query);
                    $rank   = $DB->result($result, 0, 0);
                    $rank++;

                    foreach ($tab as $newval) {
                        $query = "SELECT *
                            FROM `glpi_displaypreferences`
                            WHERE `users_id` = '" . $data['users_id'] . "'
                                  AND `num` = '$newval'
                                  AND `itemtype` = '$type'";
                        if ($result2 = $DB->doQuery($query)) {
                            if ($DB->numrows($result2) == 0) {
                                 $query = "INSERT INTO `glpi_displaypreferences`
                                         (`itemtype` ,`num` ,`rank` ,`users_id`)
                                  VALUES ('$type', '$newval', '" . $rank++ . "',
                                          '" . $data['users_id'] . "')";
                                 $DB->doQuery($query);
                            }
                        }
                    }
                }
            } else { // Add for default user
                $rank = 1;
                foreach ($tab as $newval) {
                    $query = "INSERT INTO `glpi_displaypreferences`
                                (`itemtype` ,`num` ,`rank` ,`users_id`)
                         VALUES ('$type', '$newval', '" . $rank++ . "', '0')";
                    $DB->doQuery($query);
                }
            }
        }
    }

   // must always be at the end
    $migration->executeMigration();

    return $updateresult;
}
