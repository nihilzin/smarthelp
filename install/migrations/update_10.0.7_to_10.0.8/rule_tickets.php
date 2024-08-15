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
 * @var \DBmysql $DB
 */

$DB->updateOrDie(
    'glpi_rulecriterias',
    [
        'criteria' => '_locations_id_of_item'
    ],
    [
        'criteria' => 'items_locations'
    ],
    '10.0.8 replace old rule criteria items_locations'
);
