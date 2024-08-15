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


CronTask::register('Agent', 'Cleanoldagents', DAY_TIMESTAMP, [
    'comment' => 'Clean old agents',
    'state' => CronTask::STATE_WAITING,
    'mode' => CronTask::MODE_EXTERNAL,
    'logs_lifetime' => 30
]);
