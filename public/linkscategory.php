<?php

/**
 * Contao Open Source CMS, Copyright (C) 2005-2013 Leo Feyer
 *
 */

/**
 * Run in a custom namespace, so the class can be replaced
 */
use Contao\Controller;

/**
 * Initialize the system
 */
define('TL_MODE', 'FE');
if(file_exists('../../../initialize.php')) require('../../../initialize.php');
else require('../../../../system/initialize.php');

/**
 * Liefert die Links einer Kategorie als Javascript
 *
 */
class getCategory 
{
	protected $id;

	public function __construct()
	{
		$this->id = \Input::get('id') + 0;   
	}

	public function run()
	{
		$ip = $_SERVER['REMOTE_ADDR'];

    	$objLink = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection_links WHERE published = ? AND pid = ? ORDER BY title ASC')
    									   ->execute(1, $this->id);
		if(!$objLink->next()) 
		{
			\System::log('[Linkscollection] Forwarding category ID '.$this->id.' not exist', __CLASS__.'::'.__FUNCTION__, TL_ERROR); 
			header('HTTP/1.1 501 Not Implemented');
			throw new \ErrorException('Category ID not found',2,1,basename(__FILE__),__LINE__);
		} 

		while($objLink->next())
		{
		}
		       						
		\System::log('[Linkscollection] Forwarding category ID '.$this->id.': '.$url, __CLASS__.'::'.__FUNCTION__, TL_ACCESS); 
        header('Location:'.$url);

	}
}

/**
 * Instantiate controller
 */
$objClick = new getCategory();
$objClick->run();

