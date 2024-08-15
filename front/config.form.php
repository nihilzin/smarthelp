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

use Glpi\Cache\CacheManager;

/** @var array $_UPOST */
global $_UPOST;

include('../inc/includes.php');
Session::checkRight("config", READ);

if (isset($_GET['check_version'])) {
    Session::addMessageAfterRedirect(
        Toolbox::checkNewVersionAvailable()
    );
    Html::back();
}

$config = new Config();
$_POST['id'] = Config::getConfigIDForContext('core');
if (!empty($_POST["update_auth"])) {
    $config->update($_POST);
    Html::back();
}
if (!empty($_POST["update"])) {
    $context = array_key_exists('config_context', $_POST) ? $_POST['config_context'] : 'core';

    $glpikey = new GLPIKey();
    foreach (array_keys($_POST) as $field) {
        if ($glpikey->isConfigSecured($context, $field)) {
           // Field must not be altered, it will be encrypted and never displayed, so sanitize is not necessary.
            $_POST[$field] = $_UPOST[$field];
        }
    }

    $config->update($_POST);
    Html::redirect(Toolbox::getItemTypeFormURL('Config'));
}
if (!empty($_POST['reset_opcache'])) {
    $config->checkGlobal(UPDATE);
    if (opcache_reset()) {
        Session::addMessageAfterRedirect(__('PHP OPcache reset successful'));
    }
    Html::redirect(Toolbox::getItemTypeFormURL('Config'));
}
if (!empty($_POST['reset_core_cache'])) {
    $config->checkGlobal(UPDATE);
    $cache_manager = new CacheManager();
    if ($cache_manager->getCoreCacheInstance()->clear()) {
        Session::addMessageAfterRedirect(__('GLPI cache reset successful'));
    }
    Html::redirect(Toolbox::getItemTypeFormURL('Config'));
}
if (!empty($_POST['reset_translation_cache'])) {
    $config->checkGlobal(UPDATE);
    $cache_manager = new CacheManager();
    if ($cache_manager->getTranslationsCacheInstance()->clear()) {
        Session::addMessageAfterRedirect(__('Translation cache reset successful'));
    }
    Html::redirect(Toolbox::getItemTypeFormURL('Config'));
}

Config::displayFullPageForItem($_POST['id'], ["config", "config"], [
    'formoptions'  => "data-track-changes=true"
]);
