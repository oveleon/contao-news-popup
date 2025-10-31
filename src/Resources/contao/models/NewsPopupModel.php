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

use Contao\Date;
use Contao\Model\Collection;
use Contao\NewsModel;
use Contao\System;
use Symfony\Component\HttpFoundation\Request;

/**
 * Reads and writes popup news.
 *
 * @property string $newsPopup
 *
 * @method static NewsModel|null                             findOneByNewsPopup($val, array $opt=array())
 * @method static Collection|array<NewsModel>|NewsModel|null findByNewsPopup($val, array $opt=array())
 * @method static integer                                    countByNewsPopup($id, array $opt=array())
 */
class NewsPopupModel extends NewsModel
{
    /**
     * Find published messages for popup by their parent ID.
     *
     * @param array $arrPids     An array of news archive IDs
     * @param bool  $blnFeatured If true, return only featured news, if false, return only unfeatured news
     * @param int   $intLimit    An optional limit
     * @param int   $intOffset   An optional offset
     * @param array $arrOptions  An optional options array
     *
     * @return Collection|array<NewsPopupModel>|NewsPopupModel|null A collection of models or null if there are no news
     */
    public static function findPopupPublishedByPids($arrPids, $blnFeatured = null, $intLimit = 0, $intOffset = 0, array $arrOptions = [])
    {
        if (empty($arrPids) || !\is_array($arrPids))
        {
            return null;
        }

        $t = static::$strTable;
        $arrColumns = ["$t.pid IN(".implode(',', array_map('\intval', $arrPids)).')'];

        // Popup
        $arrColumns[] = "$t.popup='1'";

        if (true === $blnFeatured)
        {
            $arrColumns[] = "$t.featured='1'";
        }
        elseif (false === $blnFeatured)
        {
            $arrColumns[] = "$t.featured=''";
        }

        // Never return unpublished elements in the back end, so they don't end up in the RSS feed
        if (!System::getContainer()->get('contao.security.token_checker')->isPreviewMode() || System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create('')))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        if (!isset($arrOptions['order']))
        {
            $arrOptions['order'] = "$t.date DESC";
        }

        $arrOptions['limit'] = $intLimit;
        $arrOptions['offset'] = $intOffset;

        return static::findBy($arrColumns, null, $arrOptions);
    }

    /**
     * Count published news items by their parent ID.
     *
     * @param array $arrPids     An array of news archive IDs
     * @param bool  $blnFeatured If true, return only featured news, if false, return only unfeatured news
     * @param array $arrOptions  An optional options array
     *
     * @return int The number of news items
     */
    public static function countPopupPublishedByPids($arrPids, $blnFeatured = null, array $arrOptions = [])
    {
        if (empty($arrPids) || !\is_array($arrPids))
        {
            return 0;
        }

        $t = static::$strTable;
        $arrColumns = ["$t.pid IN(".implode(',', array_map('\intval', $arrPids)).')'];

        if (true === $blnFeatured)
        {
            $arrColumns[] = "$t.featured='1'";
        }
        elseif (false === $blnFeatured)
        {
            $arrColumns[] = "$t.featured=''";
        }

        if (!static::isPreviewMode($arrOptions))
        {
            $time = Date::floorToMinute();
            $arrColumns[] = "$t.published='1' AND ($t.start='' OR $t.start<='$time') AND ($t.stop='' OR $t.stop>'$time')";
        }

        return static::countBy($arrColumns, null, $arrOptions);
    }
}
