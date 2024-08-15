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
 * @since 9.1
 */

use Glpi\Cache\CacheManager;

define('GLPI_ROOT', __DIR__);
ini_set('session.use_cookies', 0);

include_once(GLPI_ROOT . "/inc/based_config.php");

// Init loggers
$GLPI = new GLPI();
$GLPI->initLogger();
$GLPI->initErrorHandler();

//init cache
$cache_manager = new CacheManager();
$GLPI_CACHE = $cache_manager->getCoreCacheInstance();

$api = new Glpi\Api\APIRest();
$api->call();
