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

use Oveleon\ContaoNewsPopup\ModuleNewsPopup;

// Front end modules
$GLOBALS['FE_MOD']['news']['newspopup'] = ModuleNewsPopup::class;

// Models
$GLOBALS['TL_MODELS']['tl_popup_news'] = ModuleNewsPopup::class;
