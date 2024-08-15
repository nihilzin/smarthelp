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

/** @var \DBmysql $DB */
global $DB;

include('../inc/includes.php');

Session::checkRight("link", READ);

if (isset($_GET["lID"])) {
    $iterator = $DB->request([
        'SELECT' => ['id', 'link', 'data'],
        'FROM'   => 'glpi_links',
        'WHERE'  => [
            'id' => $_GET['lID']
        ]
    ]);

    if (count($iterator) == 1) {
        $current = $iterator->current();
        $file = $current['data'];
        $link = $current['link'];

        if ($item = getItemForItemtype($_GET["itemtype"])) {
            if ($item->getFromDB($_GET["id"])) {
                $content_filename = Link::generateLinkContents($link, $item, false);
                $content_data     = Link::generateLinkContents($file, $item, false);

                if (isset($_GET['rank']) && isset($content_filename[$_GET['rank']])) {
                    $filename = $content_filename[$_GET['rank']];
                } else {
                   // first one (the same for all IP)
                    $filename = reset($content_filename);
                }

                if (isset($_GET['rank']) && isset($content_data[$_GET['rank']])) {
                    $data = $content_data[$_GET['rank']];
                } else {
                   // first one (probably missing arg)
                    $data = reset($content_data);
                }
                header("Content-disposition: filename=\"$filename\"");
                $mime = "application/scriptfile";

                header("Content-type: " . $mime);
                header('Pragma: no-cache');
                header('Expires: 0');

                // May have several values due to network datas : use only first one
                echo $data;
            }
        }
    }
}
