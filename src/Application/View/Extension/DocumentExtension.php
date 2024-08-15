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

namespace Glpi\Application\View\Extension;

use Toolbox;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

/**
 * @since 10.0.0
 */
class DocumentExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('document_icon', [$this, 'getDocumentIcon']),
            new TwigFilter('document_size', [$this, 'getDocumentSize']),
        ];
    }

    /**
     * Returns icon URL for given document filename.
     *
     * @param string $filename
     *
     * @return string
     */
    public function getDocumentIcon(string $filename): string
    {
        /** @var array $CFG_GLPI */
        global $CFG_GLPI;

        $icon = sprintf('/pics/icones/%s-dist.png', strtolower(pathinfo($filename, PATHINFO_EXTENSION)));

        return $CFG_GLPI['root_doc'] . (file_exists(GLPI_ROOT . $icon) ? $icon : '/pics/timeline/file.png');
    }

    /**
     * Returns human readable size of file matching given path (relative to GLPI_DOC_DIR).
     *
     * @param string $filepath
     *
     * @return null|string
     */
    public function getDocumentSize(string $filepath): ?string
    {
        $fullpath = GLPI_DOC_DIR . '/' . $filepath;

        return is_readable($fullpath) ? Toolbox::getSize(filesize($fullpath)) : null;
    }
}
