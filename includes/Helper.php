<?php

namespace Samson\Linksammlung;
 
class Helper
{

	/**
	 * Current object instance
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * Klasse initialisieren
	 */
	public function __construct()
	{
	}


	/**
	 * Return the current object instance (Singleton)
	 * @return BannerCheckHelper
	 */
	public static function getInstance()
	{
		if (self::$instance === null)
		{
			self::$instance = new \Samson\Linksammlung\Helper();
		}
	
		return self::$instance;
	}


	/**
	 * Schreibt die Kategorien in das Frontendformular aus dem Formular-Generator
	 *
	 */
	public function setCategoriesToForm(Widget $objWidget, $strForm, $arrForm)
	{
		$objWidget->class = 'myclass';
		return $objWidget;
	}

}
