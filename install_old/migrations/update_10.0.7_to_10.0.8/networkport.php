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

$migration->changeField(
    NetworkPortFiberchannel::getTable(),
    'wwn',
    'wwn',
    "varchar(50) DEFAULT ''",
);
