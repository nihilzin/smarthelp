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
 * @var \Migration $migration
 */

// Change action type for "itilfollowup_template" and "task_template"
$query = $DB->buildUpdate(
    "glpi_ruleactions",
    [
        "action_type" => "append"
    ],
    [
        "action_type" => "assign",
        "OR" => [
            ["field" => "itilfollowup_template"],
            ["field" => "task_template"],
        ]
    ]
);
$migration->addPostQuery($query);
