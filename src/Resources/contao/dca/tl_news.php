<?php
// Extend the default palette
Contao\CoreBundle\DataContainer\PaletteManipulator::create()
    ->addField('popup', 'featured', Contao\CoreBundle\DataContainer\PaletteManipulator::POSITION_AFTER)
    ->applyToPalette('default', 'tl_news')
    ->applyToPalette('internal', 'tl_news')
    ->applyToPalette('article', 'tl_news')
    ->applyToPalette('external', 'tl_news')
;

// Fields
$GLOBALS['TL_DCA']['tl_news']['fields']['popup'] = [
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => ['tl_class'=>'w50 cbx m12'],
    'sql'                     => "char(1) NOT NULL default ''"
];
