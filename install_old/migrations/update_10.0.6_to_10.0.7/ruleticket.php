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
 * @var \DBmysql $DB
 */

// Replace old rule criteria itilcategories_id_cn
$DB->updateOrDie(
    'glpi_rulecriterias',
    [
        'criteria' => 'itilcategories_id'
    ],
    [
        'criteria' => 'itilcategories_id_cn'
    ],
    '10.0.7 replace old rule criteria itilcategories_id_cn'
);
