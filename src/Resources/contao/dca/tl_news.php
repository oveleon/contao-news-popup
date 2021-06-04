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
$GLOBALS['TL_DCA']['tl_news']['fields']['popup'] = array
(
    'exclude'                 => true,
    'filter'                  => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "char(1) NOT NULL default ''"
);
