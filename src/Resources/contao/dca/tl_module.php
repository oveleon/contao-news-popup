<?php
// Palette
$GLOBALS['TL_DCA']['tl_module']['palettes']['newspopup'] = '{title_legend},name,headline,type;{config_legend},news_archives,news_readerModule,news_featured,news_order;{template_legend:hide},news_metaFields,news_template,customTpl,news_popup_placement;{image_legend:hide},imgSize;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID';

// Fields
$GLOBALS['TL_DCA']['tl_module']['fields']['news_popup_placement'] = array
(
    'exclude'                 => true,
    'inputType'               => 'select',
    'options'                 => ['top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right'],
    'reference'               => &$GLOBALS['TL_LANG']['tl_module'],
    'eval'                    => array('tl_class'=>'w50 clr'),
    'sql'                     => "varchar(16) NOT NULL default 'bottom-right'"
);


// Field callback
$GLOBALS['TL_DCA']['tl_module']['fields']['news_template']['load_callback'][] = [Oveleon\ContaoNewsPopup\Popup::class, 'onLoadModuleTemplate'];