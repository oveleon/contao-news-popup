<?php

namespace Oveleon\ContaoNewsPopup;

use Contao\DataContainer;
use Contao\Controller;

class Popup extends Controller
{
    public function onLoadModuleTemplate(string $strValue, DataContainer $dc): string
    {
        if($dc->activeRecord->type !== 'newspopup')
        {
            return $strValue;
        }

        $GLOBALS['TL_DCA']['tl_module']['fields']['news_template']['eval']['includeBlankOption'] = false;
        $GLOBALS['TL_DCA']['tl_module']['fields']['news_template']['options_callback'] = static function ()
        {
            return Controller::getTemplateGroup('news_popup_');
        };

        return $strValue;
    }
}