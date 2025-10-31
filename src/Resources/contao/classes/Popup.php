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

use Contao\Controller;
use Contao\DataContainer;

class Popup extends Controller
{
    public function onLoadModuleTemplate(string $strValue, DataContainer $dc): string
    {
        if ('newspopup' !== $dc->activeRecord->type)
        {
            return $strValue;
        }

        $GLOBALS['TL_DCA']['tl_module']['fields']['news_template']['eval']['includeBlankOption'] = false;
        $GLOBALS['TL_DCA']['tl_module']['fields']['news_template']['options_callback'] = static fn () => Controller::getTemplateGroup('news_popup_');

        return $strValue;
    }
}
