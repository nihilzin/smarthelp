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

/** @file
 * @brief
 */

include('../inc/includes.php');
header("Content-Type: text/html; charset=UTF-8");
Html::header_nocache();

Session::checkLoginUser();

if (isset($_POST["projects_id"])) {
    $condition = ['glpi_projecttasks.projectstates_id' => ['<>', 3]];

    if ($_POST["projects_id"] > 0) {
        $condition['glpi_projecttasks.projects_id'] = $_POST['projects_id'];
    }

    $p = ['itemtype'     => ProjectTask::getType(),
        'entity_restrict' => $_POST['entity_restrict'],
        'myname'          => $_POST["myname"],
        'condition'       => $condition,
        'rand'            => $_POST["rand"]
    ];

    if (isset($_POST["used"]) && !empty($_POST["used"])) {
        if (isset($_POST["used"])) {
            $p["used"] = $_POST["used"];
        }
    }

    ProjectTask::dropdown($p);
}
