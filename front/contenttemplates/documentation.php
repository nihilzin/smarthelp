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

include('../../inc/includes.php');

use Glpi\ContentTemplates\TemplateManager;
use Glpi\Http\Response;
use Michelf\MarkdownExtra;

// Check mandatory parameter
$preset = $_GET['preset'] ?? null;
if (is_null($preset)) {
    Response::sendError(400, "Missing mandatory 'preset' parameter", Response::CONTENT_TYPE_TEXT_HTML);
}

Html::includeHeader(__("Template variables documentation"));
echo "<body class='documentation-page'>";
echo "<div id='page'>";
echo "<div class='documentation documentation-large'>";

// Parse markdown
$md = new MarkdownExtra();
$md->header_id_func = function ($headerName) {
    return Toolbox::slugify($headerName, '');
};
echo $md->transform(TemplateManager::generateMarkdownDocumentation($preset));

echo "</div>";
echo "</div>";

// Footer closes main and div
echo "<main>";
echo "<div>";
Html::nullFooter();
