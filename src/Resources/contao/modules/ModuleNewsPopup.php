<?php

namespace Oveleon\ContaoNewsPopup;

use Contao\BackendTemplate;
use Contao\Config;
use Contao\Environment;
use Contao\Input;
use Contao\Model\Collection;
use Contao\ModuleNewsList;
use Contao\NewsModel;
use Contao\Pagination;
use Contao\System;
use Patchwork\Utf8;

/**
 * Front end module "news popup".
 *
 * @property array  $news_archives
 * @property string $news_featured
 * @property string $news_order
 *
 * @author Daniele Sciannimanica <https://github.com/doishub>
 */
class ModuleNewsPopup extends ModuleNewsList
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_newspopup';

	/**
	 * Display a wildcard in the back end
	 *
	 * @return string
	 */
	public function generate()
	{
		$request = System::getContainer()->get('request_stack')->getCurrentRequest();

		if ($request && System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest($request))
		{
			$objTemplate = new BackendTemplate('be_wildcard');
			$objTemplate->wildcard = '### ' . Utf8::strtoupper($GLOBALS['TL_LANG']['FMD']['newspopup'][0]) . ' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->name;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

    /**
     * Generate the module
     */
    protected function compile()
    {
        // Handle featured news
        if ($this->news_featured == 'featured')
        {
            $blnFeatured = true;
        }
        elseif ($this->news_featured == 'unfeatured')
        {
            $blnFeatured = false;
        }
        else
        {
            $blnFeatured = null;
        }

        $this->Template->articles = array();

        $objArticles = $this->fetchItems($this->news_archives, $blnFeatured, 1, 0);

        // Add the articles
        if ($objArticles !== null)
        {
            $this->Template->articles = $this->parseArticles($objArticles);
        }

        $this->Template->archives = $this->news_archives;
    }

	/**
	 * Fetch the matching items
	 *
	 * @param array   $newsArchives
	 * @param boolean $blnFeatured
	 * @param integer $limit
	 * @param integer $offset
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

				if ($objCollection === null || $objCollection instanceof Collection)
				{
					return $objCollection;
				}
			}
		}

		// Determine sorting
		$t = NewsPopupModel::getTable();
		$order = '';

		if ($this->news_featured == 'featured_first')
		{
			$order .= "$t.featured DESC, ";
		}

		switch ($this->news_order)
		{
			case 'order_headline_asc':
				$order .= "$t.headline";
				break;

			case 'order_headline_desc':
				$order .= "$t.headline DESC";
				break;

			case 'order_random':
				$order .= "RAND()";
				break;

			case 'order_date_asc':
				$order .= "$t.date";
				break;

			default:
				$order .= "$t.date DESC";
		}

		return NewsPopupModel::findPopupPublishedByPids($newsArchives, $blnFeatured, $limit, $offset, array('order'=>$order));
	}
}
