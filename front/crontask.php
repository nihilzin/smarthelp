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
 * Search engine from cron tasks
 */

/** @var array $CFG_GLPI */
global $CFG_GLPI;

include('../inc/includes.php');

Session::checkRight("config", UPDATE);

Html::header(CronTask::getTypeName(Session::getPluralNumber()), $_SERVER['PHP_SELF'], 'config', 'crontask');

$crontask = new CronTask();
if ($crontask->getNeedToRun(CronTask::MODE_INTERNAL)) {
    Html::displayTitle(
        "",
        __('Next run'),
        "<i class='fas fa-step-forward fa-lg me-2'></i>" . sprintf(__('Next task to run: %s'), $crontask->fields['name'])
    );
    echo "<div class='btn-group flex-wrap mb-3 ms-2'>";
    Html::showSimpleForm(
        $crontask->getFormURL(),
        ['execute' => $crontask->fields['name']],
        __('Execute')
    );
    echo "</div>";
} else {
    Html::displayTitle(
        "",
        __('No action pending'),
        "<i class='fas fa-check fa-lg me-2'></i>" . __('No action pending')
    );
}

if (
    $CFG_GLPI['cron_limit'] < countElementsInTable(
        'glpi_crontasks',
        ['frequency' => MINUTE_TIMESTAMP]
    )
) {
    Html::displayTitle(
        '',
        '',
        "<i class='fas fa-exclamation-triangle fa-lg me-2'></i>" .
        __('You have more automatic actions which need to run each minute than the number allow each run. Increase this config.')
    );
}

Search::show('CronTask');

Html::footer();
