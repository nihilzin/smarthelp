<?php

/*!
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

$migration->addConfig([
    'planning_work_days' => exportArrayToDB([0, 1, 2, 3, 4, 5, 6])
]);
