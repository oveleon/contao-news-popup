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

use Contao\CoreBundle\DataContainer\PaletteManipulator;

// Extend the default palette
PaletteManipulator::create()
    ->addField('popup', 'featured')
    ->applyToPalette('default', 'tl_news')
    ->applyToPalette('internal', 'tl_news')
    ->applyToPalette('article', 'tl_news')
    ->applyToPalette('external', 'tl_news')
;

// Fields
$GLOBALS['TL_DCA']['tl_news']['fields']['popup'] = [
    'exclude' => true,
    'filter' => true,
    'inputType' => 'checkbox',
    'eval' => ['tl_class' => 'w50 cbx m12'],
    'sql' => "char(1) NOT NULL default ''",
];
