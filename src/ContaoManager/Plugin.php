<?php

declare(strict_types=1);

/*
 * This file is part of Contao News Popup.
 *
 * @package     contao-news-popup
 * @license     MIT
 * @author      Daniele Sciannimanica  <https://github.com/doishub>
 * @copyright   Oveleon                <https://www.oveleon.de/>
 */

namespace Oveleon\ContaoNewsPopup\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\NewsBundle\ContaoNewsBundle;
use Oveleon\ContaoNewsPopup\ContaoNewsPopup;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoNewsPopup::class)
                ->setReplace(['contao-news-popup'])
                ->setLoadAfter([ContaoCoreBundle::class, ContaoNewsBundle::class]),
        ];
    }
}
