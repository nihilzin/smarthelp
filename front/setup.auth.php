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

use Glpi\Application\View\TemplateRenderer;

include('../inc/includes.php');

Session::checkRight("config", READ);

Html::header(__('External authentication sources'), $_SERVER['PHP_SELF'], "config", "auth", -1);

echo TemplateRenderer::getInstance()->render(
    'pages/setup/authentication.html.twig',
    [
        'can_use_ldap' => Toolbox::canUseLdap(),
    ]
);

Html::footer();
