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
 * @var \Migration $migration
 */

$migration->addRightByInterface('reminder_public', Reminder::PERSONAL, 'central');
$migration->addRightByInterface('rssfeed_public', RSSFeed::PERSONAL, 'central');
