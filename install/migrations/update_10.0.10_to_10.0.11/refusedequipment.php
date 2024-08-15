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

$migration->changeField(
    RefusedEquipment::getTable(),
    'ip',
    'ip',
    "text"
);

$migration->changeField(
    RefusedEquipment::getTable(),
    'mac',
    'mac',
    "text"
);
