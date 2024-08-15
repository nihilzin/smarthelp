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

Session::checkRightsOr('project', [Project::READALL, Project::READMY]);

Html::header(Project::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], "tools", "project");

Search::show('Project');

Html::footer();
