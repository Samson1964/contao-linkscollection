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
 * Class LinkClick
 *
 */
class LinkRebuilder
{

	public function run()
	{
		// Startzeit für neue Links
		$duration = time() - ($GLOBALS['TL_CONFIG']['linkscollection_new_duration'] * 86400);

		// Anzahl der Links in jede Kategorie eintragen, dazu zuerst alle veröffentlichten Links laden 
		$objLinks = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection_links WHERE published = ?')
							   				->execute(1);

		// Links in jeweiligen Array-Wert für die Kategorie hochzählen
		$links_all = array();
		$links_self = array();
		$links_new = array();
		$links_newbie = array();

		while($objLinks->next()) 
		{
			$links_self[$objLinks->pid]++; // Link in eigner Kategorie addieren
			if($objLinks->initdate >= $duration) $links_new[$objLinks->pid]++; // Neuen Link zählen
			$cats = $this->foundParents($objLinks->pid); // Oberkategorien finden
			foreach($cats as $cat)
			{
				$links_all[$cat]++;
			}
		}

		foreach($links_new as $key => $value) 
		{
			$cats = $this->foundParents($key); // Oberkategorien finden
			foreach($cats as $cat)
			{
				$links_newbie[$cat]++;
			}
		}
		
		// Anzahl neue Links nullen
       	\Database::getInstance()->prepare('UPDATE tl_linkscollection SET links_new = ?')
    							->execute(0);

		// Anzahl in Kategorien eintragen
		foreach($links_all as $key => $value)
		{
			if($key)
			{
				$set = array
				(
					'links_all'  => $value,
					'links_self' => $links_self[$key],
					'links_new'  => $links_newbie[$key]
				);
	        	\Database::getInstance()->prepare('UPDATE tl_linkscollection %s WHERE id = ?')
    	    							->set($set)
    	    							->execute($key);
			}
		}
		
		\System::log('[Linkscollection] Rebuild categories', __CLASS__.'::'.__FUNCTION__, TL_CRON); 

		echo "<pre>";
		print_r($links_all);
		ksort($links_new);
		print_r($links_new);
		print_r($links_newbie);
		echo "</pre>";

	}
	
	protected function foundParents($id)
	{
		$katids = array($id);
		
		do 
		{
			if($id)
			{
				$objTemp = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection WHERE published = ? AND id = ?')
								   			       ->execute(1, $id);
				$katids[] = $objTemp->pid;
				$id = $objTemp->pid;				   			       
			}
		}
		while($id > 0);
		
		return $katids;
	}
}

/**
 * Instantiate controller
 */
$objBuild = new LinkRebuilder();
$objBuild->run();

