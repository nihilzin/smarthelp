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

include('../inc/includes.php');

Session::checkRightsOr('project', [Project::READALL, Project::READMY, ProjectTask::READMY]);

Html::header(ProjectTask::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "project", 'task');

Search::show('ProjectTask');

Html::footer();
