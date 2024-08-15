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

// Remove '' default values on glpi_impactcontexts.positions
// MySQL does not allow default values on TEXT fields, while MariaDB does
// Default was removed in installation file GLPI 9.5.4, see #8415
$migration->changeField('glpi_impactcontexts', 'positions', 'positions', 'mediumtext NOT NULL');
