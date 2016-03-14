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
 * Class LinkSearch
 *
 */
class LinkSearch
{
	protected $keywords;
	protected $strTemplate = 'mod_linkscollection_linkrow';
	var $duration_new;

	public function __construct()
	{
		$this->keywords = \Input::get('s');
		$this->Template = new \FrontendTemplate($this->strTemplate);
		$this->duration_new = time() - ($GLOBALS['TL_CONFIG']['linkscollection_new_duration'] * 86400);
	}

	public function run()
	{
		if(strlen($this->keywords) >= 3)
		{
    		$objLinks = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection_links WHERE published = ? AND (title LIKE ? OR url LIKE ? OR text LIKE ?) ORDER BY hits DESC')
    										   ->execute(1, '%'.$this->keywords.'%', '%'.$this->keywords.'%', '%'.$this->keywords.'%');

			$links = array();
			if($objLinks->numRows > 1)
			{
				// Datensätze anzeigen
				while($objLinks->next())
				{
					$class = ($class == 'odd') ? 'even' : 'odd';
					// HARDCODED!!! problem_url von Linkscollection ermitteln!
					$links[] = array
					(
						'title'       => $objLinks->title,
						'url'         => 'system/modules/linkscollection/public/go.php?id='.$objLinks->id,
						'icon'        => \Linkscollection::getFavicon($objLinks->id),
						'language'    => \Linkscollection::getLanguageIcon($objLinks->language),
						'new'         => $objLinks->newWindow,
						'text'        => $objLinks->text,
						'popular'     => $objLinks->popular,
						'hits'        => $objLinks->hits,
						'problem_url' => 'linksammlung/link/'.$objLinks->id.'.html',
						'class'       => $class,
						'newLink'     => ($objLinks->initdate >= $this->duration_new) ? NEWICON : '',
						'webarchiv'   => $objLinks->webarchiv
					);
				}

				// Template füllen
				$this->Template->links = $links;
				echo $objLinks->numRows.' Link(s) gefunden';
				echo '<ul>';
				echo $this->Template->parse();
				echo '<ul>';
			}
			else
			{
				echo 'Keine Links gefunden';
			}
			\System::log('[Linkscollection] Searching "'.$this->keywords.'" - found '.$objLink->numRows.' row(s)', __CLASS__.'::'.__FUNCTION__, TL_ACCESS);
		}
		else
		{
			echo 'Suchbegriff zu kurz';
			\System::log('[Linkscollection] Searching "'.$this->keywords.'" - to short', __CLASS__.'::'.__FUNCTION__, TL_ERROR);
		}

	}
}

/**
 * Instantiate controller
 */
$objClick = new LinkSearch();
$objClick->run();

