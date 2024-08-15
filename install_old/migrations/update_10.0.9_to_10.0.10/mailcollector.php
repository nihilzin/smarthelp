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
 * @var array $ADDTODISPLAYPREF
 * @var \Migration $migration
 */

/* Add `last_collect_date` to some glpi_mailcollectors */
$migration->addField('glpi_mailcollectors', 'last_collect_date', 'timestamp');
$migration->addKey('glpi_mailcollectors', 'last_collect_date', 'last_collect_date');
$ADDTODISPLAYPREF['MailCollector'] = [23];
