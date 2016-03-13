<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2016 Leo Feyer
 *
 * @license LGPL-3.0+
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'BackendLinkscollectionFilter\Filter'      => 'system/modules/linkscollection/classes/Filter.php',
	'Linkscollection'                          => 'system/modules/linkscollection/includes/Linkscollection.php',
	'Linksammlung'                             => 'system/modules/linkscollection/classes/Linksammlung.php',
	'FaviconDownloader'                        => 'system/modules/linkscollection/includes/FaviconDownloader.class.php',
	'LinkRebuilder'                            => 'system/modules/linkscollection/public/rebuild.php',
	'Formular'                                 => 'system/modules/linkscollection/libraries/Formular.php',
	'FormularTL_DCA'                           => 'system/modules/linkscollection/libraries/FormularTL_DCA.php',
	'Nibble\NibbleForms\NibbleForm'            => 'system/modules/linkscollection/libraries/nibble/NibbleForm.php',
	'Nibble\NibbleForms\Useful'                => 'system/modules/linkscollection/libraries/nibble/Useful.php',
	'Nibble\NibbleForms\Field'                 => 'system/modules/linkscollection/libraries/nibble/Field.php',
	'Nibble\NibbleForms\Field\BaseOptions'     => 'system/modules/linkscollection/libraries/nibble/Field/BaseOptions.php',
	'Nibble\NibbleForms\Field\Captcha'         => 'system/modules/linkscollection/libraries/nibble/Field/Captcha.php',
	'Nibble\NibbleForms\Field\Checkbox'        => 'system/modules/linkscollection/libraries/nibble/Field/Checkbox.php',
	'Nibble\NibbleForms\Field\Email'           => 'system/modules/linkscollection/libraries/nibble/Field/Email.php',
	'Nibble\NibbleForms\Field\File'            => 'system/modules/linkscollection/libraries/nibble/Field/File.php',
	'Nibble\NibbleForms\Field\Hidden'          => 'system/modules/linkscollection/libraries/nibble/Field/Hidden.php',
	'Nibble\NibbleForms\Field\MultipleOptions' => 'system/modules/linkscollection/libraries/nibble/Field/MultipleOptions.php',
	'Nibble\NibbleForms\Field\MultipleSelect'  => 'system/modules/linkscollection/libraries/nibble/Field/MultipleSelect.php',
	'Nibble\NibbleForms\Field\Number'          => 'system/modules/linkscollection/libraries/nibble/Field/Number.php',
	'Nibble\NibbleForms\Field\Options'         => 'system/modules/linkscollection/libraries/nibble/Field/Options.php',
	'Nibble\NibbleForms\Field\Password'        => 'system/modules/linkscollection/libraries/nibble/Field/Password.php',
	'Nibble\NibbleForms\Field\Radio'           => 'system/modules/linkscollection/libraries/nibble/Field/Radio.php',
	'Nibble\NibbleForms\Field\Select'          => 'system/modules/linkscollection/libraries/nibble/Field/Select.php',
	'Nibble\NibbleForms\Field\Text'            => 'system/modules/linkscollection/libraries/nibble/Field/Text.php',
	'Nibble\NibbleForms\Field\TextArea'        => 'system/modules/linkscollection/libraries/nibble/Field/TextArea.php',
	'Nibble\NibbleForms\Field\Url'             => 'system/modules/linkscollection/libraries/nibble/Field/Url.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'be_linkscollection'           => 'system/modules/linkscollection/templates',
	'be_linklist'                  => 'system/modules/linkscollection/templates',
	'mod_linkscollection'          => 'system/modules/linkscollection/templates',
	'mod_linkscollection_link'     => 'system/modules/linkscollection/templates',
	'mod_linkscollection_toplinks' => 'system/modules/linkscollection/templates',
	'mod_linkscollection_newlinks' => 'system/modules/linkscollection/templates',
	'mod_linkscollection_linkrow'  => 'system/modules/linkscollection/templates',
));
