<?php

declare(strict_types=1);

namespace Oveleon\ContaoNewsPopup\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Oveleon\ContaoNewsPopup\ContaoNewsPopup;
use Contao\NewsBundle\ContaoNewsBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(ContaoNewsPopup::class)
                ->setReplace(['contao-news-popup'])
                ->setLoadAfter([ContaoCoreBundle::class, ContaoNewsBundle::class]),
        ];
    }
}
