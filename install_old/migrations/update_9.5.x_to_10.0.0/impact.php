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

/** Impact Relations improvements */

$migration->changeField('glpi_impactcontexts', 'positions', 'positions', 'mediumtext', [
    'after' => 'id',
    'value' => '',
]);
