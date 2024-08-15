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

// Drop unexpected `['0' => 'system_user']` config added by buggy 9.5.x -> 10.0.0 migration.
Config::deleteConfigurationValues('core', ['0']);
