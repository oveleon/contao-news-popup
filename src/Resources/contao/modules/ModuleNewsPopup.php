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

namespace Oveleon\ContaoNewsPopup;

use Contao\BackendTemplate;
use Contao\Model\Collection;
use Contao\ModuleNewsList;
use Contao\NewsModel;
use Contao\System;

/**
 * Front end module "news popup".
 *
 * @property array  $news_archives
 * @property string $news_featured
 * @property string $news_order
 */
class ModuleNewsPopup extends ModuleNewsList
{
    /**
     * Template.
     *
     * @var string
     */
    protected $strTemplate = 'mod_newspopup';

    /**
     * Display a wildcard in the back end.
     *
     * @return string
     */
    public function generate()
    {
        $request = System::getContainer()->get('request_stack')->getCurrentRequest();

        if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
        {
            $objTemplate = new BackendTemplate('be_wildcard');
            $objTemplate->wildcard = '### '.mb_strtoupper((string) $GLOBALS['TL_LANG']['FMD']['newspopup'][0]).' ###';
            $objTemplate->title = $this->headline;
            $objTemplate->id = $this->id;
            $objTemplate->link = $this->name;
            $objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id='.$this->id;

            return $objTemplate->parse();
        }

        return parent::generate();
    }

    /**
     * Generate the module.
     */
    protected function compile(): void
    {
        // Handle featured news
        if ('featured' === $this->news_featured)
        {
            $blnFeatured = true;
        }
        elseif ('unfeatured' === $this->news_featured)
        {
            $blnFeatured = false;
        }
        else
        {
            $blnFeatured = null;
        }

        $this->Template->articles = [];

        $objArticles = $this->fetchItems($this->news_archives, $blnFeatured, 1, 0);

        // Add the articles
        if (null !== $objArticles)
        {
            $this->Template->articles = $this->parseArticles($objArticles);
        }

        $this->Template->archives = $this->news_archives;
    }

    /**
     * Fetch the matching items.
     *
     * @param array $newsArchives
     * @param bool  $blnFeatured
     * @param int   $limit
     * @param int   $offset
     *
     * @return Collection|NewsModel|null
     */
    protected function fetchItems($newsArchives, $blnFeatured, $limit, $offset)
    {
        // HOOK: add custom logic
        if (isset($GLOBALS['TL_HOOKS']['newsPopupFetchItems']) && \is_array($GLOBALS['TL_HOOKS']['newsPopupFetchItems']))
        {
            foreach ($GLOBALS['TL_HOOKS']['newsPopupFetchItems'] as $callback)
            {
                if (($objCollection = System::importStatic($callback[0])->{$callback[1]}($newsArchives, $blnFeatured, $limit, $offset, $this)) === false)
                {
                    continue;
                }

                if (null === $objCollection || $objCollection instanceof Collection)
                {
                    return $objCollection;
                }
            }
        }

        // Determine sorting
        $t = NewsPopupModel::getTable();
        $order = '';

        if ('featured_first' === $this->news_featured)
        {
            $order .= "$t.featured DESC, ";
        }

        match ($this->news_order)
        {
            'order_headline_asc' => $order .= "$t.headline",
            'order_headline_desc' => $order .= "$t.headline DESC",
            'order_random' => $order .= 'RAND()',
            'order_date_asc' => $order .= "$t.date",
            default => $order .= "$t.date DESC",
        };

        return NewsPopupModel::findPopupPublishedByPids($newsArchives, $blnFeatured, $limit, $offset, ['order' => $order]);
    }
}
