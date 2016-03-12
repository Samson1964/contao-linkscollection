<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */

class Linkscollection
{

	public static function getFavicon($id)
	{
		$suffixes = array('ico','gif','png','jpg'); // Erlaubte Favicon-Endungen
		$string = 'system/modules/linkscollection/assets/images/favicon.png'; // Standardicon setzen

		foreach($suffixes as $suffix)
		{
			$url = 'system/modules/linkscollection/assets/favicons/'.$id.'.'.$suffix;
			if(file_exists(TL_ROOT.'/'.$url))
			{
				$string = $url;
				break;
			}
		}

		return $string;								                              
	}
	
	/**
	 * Liefert eine Breadcrumb-Navigation der Kategorien
	 * @param string	Serialisiertes Array mit den Daten
	 * @return array
	 */
	public static function Breadcrumb($category)
	{
		// Breadcrumb-Navigation erstellen
		$breadcrumb = array();
		$x = 0;
		while($category > 0)
		{
			$objTemp = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection WHERE published = ? AND id = ?')
							   			       ->execute(1, $category);
			$breadcrumb[] = '<a href="contao/main.php?do=linkscollection&table=tl_linkscollection_links&id='.$objTemp->id.'" title="Links der Kategorie '.$objTemp->title.' bearbeiten">'.$objTemp->title.'</a>';
			$category = $objTemp->pid;
			$x++;
		}

		$string = implode(' > ', array_reverse($breadcrumb));		
		return $string;
	}

	/**
	 * Listet eine Analyse der Links auf
	 * @param string	Serialisiertes Array mit den Daten
	 * @return array
	 */
	public function Linkanalyse(DataContainer $dc)
	{
		if(\Input::get('key') != 'analyse')
		{
			return '';
		}

		$content = '<div id="tl_listing" class="tl_listing_container"><ul class="tl_listing">';

		// Veröffentlichte Kategorien/Links laden
		$objCats = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection WHERE published = ?')
											->execute(1);
		$objLinks = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection_links WHERE published = ?')
											->execute(1);
		
		// Ausgabe des 1. Kopfes
		$content .= '
	  	<li class="tl_linkanalyse_head">
	  		<div class="tl_left">
	  			<label>Veröffentlicht:</label>
	  			<b>'.$objCats->numRows.'</b> Kategorien und <b>'.$objLinks->numRows.'</b> Links
	  		</div>
	  		<div class="tl_right">&nbsp;</div>
	  		<div style="clear:both"></div>
	  	</li>';

		// Fehlerhafte Links
		$content .= '
	  	<li class="tl_linkanalyse_top">
	  		<div class="tl_left">
	  			Fehlerhafte Links
	  		</div>
	  		<div class="tl_right">&nbsp;</div>
	  		<div style="clear:both"></div>
	  	</li>';

		if($objLinks->numRows > 1)
		{
			while($objLinks->next()) 
			{
				if($objLinks->problemcount > 0)
				{
					$content .= '
			  		<li onmouseout="Theme.hoverDiv(this,0)" onmouseover="Theme.hoverDiv(this,1)" class="tl_linkanalyse_row" style="">
						<div class="tl_left">
							<strong>'.$objLinks->title.'</strong> ('.$objLinks->url.') 
							<br><i>'.date("d.m.Y, H:i", $objLinks->problemdate).'</i> 
							<i>- '.$objLinks->problemcount.' Meldungen</i> 
						</div> 
						<div class="tl_right">
							<a class="edit" title="" href="contao/main.php?do=linkscollection&amp;table=tl_linkscollection_links&amp;act=edit&amp;id='.$objLinks->id.'&amp;rt='.\Input::get('rt').'&amp;ref='.\Input::get('ref').'"><img width="12" height="16" alt="Link bearbeiten" src="system/themes/default/images/edit.gif"></a>
							<a class="editheader" title="" href="contao/main.php?do=linkscollection&amp;table=tl_linkscollection_links&amp;id='.$objLinks->pid.'&amp;rt='.\Input::get('rt').'&amp;ref='.\Input::get('ref').'"><img width="16" height="16" alt="Links der Kategorie bearbeiten" src="system/themes/default/images/header.gif"></a>
						</div>
						<div style="clear:both"></div>
					</li>';
				}
			}
		}

		// Unveröffentlichte Kategorien/Links laden
		$objCats = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection WHERE published = ?')
											->execute('');
		$objLinks = \Database::getInstance()->prepare('SELECT * FROM tl_linkscollection_links WHERE published = ?')
											->execute('');
		
		// Unveröffentlichte Links
		$content .= '
	  	<li class="tl_linkanalyse_head">
	  		<div class="tl_left">
	  			<label>Unveröffentlicht:</label>
	  			<b>'.$objCats->numRows.'</b> Kategorien und <b>'.$objLinks->numRows.'</b> Links
	  		</div>
	  		<div class="tl_right">&nbsp;</div>
	  		<div style="clear:both"></div>
	  	</li>';

		$content .= '
	  	<li class="tl_linkanalyse_top">
	  		<div class="tl_left">
	  			Unveröffentlichte Links
	  		</div>
	  		<div class="tl_right">&nbsp;</div>
	  		<div style="clear:both"></div>
	  	</li>';

		// Unveröffentlichte ausgeben
		if($objLinks->numRows > 1)
		{
			while($objLinks->next()) 
			{
				$content .= '
			  	<li onmouseout="Theme.hoverDiv(this,0)" onmouseover="Theme.hoverDiv(this,1)" class="tl_linkanalyse_row" style="">
					<div class="tl_left">
						<strong>'.$objLinks->title.'</strong> ('.$objLinks->url.') 
						<br><i>'.date("d.m.Y H:i", $objLinks->initdate).'</i> 
						<i>von '.$objLinks->name.'</i> 
					</div> 
					<div class="tl_right">
						<a class="edit" title="" href="contao/main.php?do=linkscollection&amp;table=tl_linkscollection_links&amp;act=edit&amp;id='.$objLinks->id.'&amp;rt='.\Input::get('rt').'&amp;ref='.\Input::get('ref').'"><img width="12" height="16" alt="Link bearbeiten" src="system/themes/default/images/edit.gif"></a>
						<a class="editheader" title="" href="contao/main.php?do=linkscollection&amp;table=tl_linkscollection_links&amp;id='.$objLinks->pid.'&amp;rt='.\Input::get('rt').'&amp;ref='.\Input::get('ref').'"><img width="16" height="16" alt="Links der Kategorie bearbeiten" src="system/themes/default/images/header.gif"></a>
					</div>
					<div style="clear:both"></div>
				</li>';
			}
		}

		$content .= '</ul></div>';

		return $content;
	}


	/**
	 * Listet alle Links auf
	 * @param string	Serialisiertes Array mit den Daten
	 * @return array
	 */
	public function Linklist(DataContainer $dc)
	{
		// Hilfsarrays für Filter/Suche
		$where = array();
		$value = array();
		
		// Standardabfrage
		$query = "SELECT * FROM tl_linkscollection_links";

		$Template = new \BackendTemplate('be_linklist');

		$Template->request = ampersand(\Environment::getInstance()->request, true);

		$Template->links = array();

		// Set default variables
		$Template->apply = $GLOBALS['TL_LANG']['MSC']['apply'];

		// Session laden
		$session = \Session::getInstance()->getData();
		$filter = 'tl_linkscollection_list'; // Sessionname
		
		// Filter, Suche und Limit setzen
		if (\Input::post('FORM_SUBMIT') == 'tl_filters' || \Input::post('FORM_SUBMIT') == 'tl_filters_limit')
		{

			// Suchfeld in Session schreiben, Suchbegriff vorerst nicht
			$session['search'][$filter]['value'] = '';
			$session['search'][$filter]['field'] = \Input::post('tl_field', true);

			// Validität des Regex im Suchbegriff prüfen
			if (\Input::postRaw('tl_value') != '')
			{
				try
				{
					\Database::getInstance()->prepare("SELECT * FROM tl_linkscollection_links WHERE ".\Input::post('tl_field', true)." REGEXP ?")
								   			->limit(1)
								   			->execute(\Input::postRaw('tl_value'));

					$session['search'][$filter]['value'] = \Input::postRaw('tl_value');
				}
				catch (\Exception $e) {}
			}

			// Limit prüfen
			$strLimit = \Input::post('tl_limit');
			if ($strLimit == 'tl_limit')
			{
				unset($session['filter'][$filter]['limit']);
			}
			else
			{
				// Benutzereingaben validieren
				if ($strLimit == 'all' || preg_match('/^[0-9]+,[0-9]+$/', $strLimit))
				{
					$session['filter'][$filter]['limit'] = $strLimit;
				}
			}

			\Session::getInstance()->setData($session);
			\Controller::reload();
		}

		// Suchbegriff zur Abfrage hinzufügen
		if ($session['search'][$filter]['value'] != '')
		{
			$where[] = "CAST(" . $session['search'][$filter]['field'] . " AS CHAR) REGEXP ?";
			$value[] = $session['search'][$filter]['value'];

			$Template->searchClass = ' active';
		}

		// Suchoptionen
		$fields = array('title', 'url', 'text');
		$options = '';

		foreach ($fields as $field)
		{
			$options .= sprintf('<option value="%s"%s>%s</option>', $field, (($field == $session['search'][$filter]['field']) ? ' selected' : ''), (is_array($GLOBALS['TL_LANG']['tl_linkscollection_list'][$field]) ? $GLOBALS['TL_LANG']['tl_linkscollection_list'][$field][0] : $GLOBALS['TL_LANG']['tl_linkscollection_list'][$field]));
		}

		$Template->searchOptions = $options;
		$Template->keywords = specialchars($session['search'][$filter]['value']);
		$Template->search = specialchars($GLOBALS['TL_LANG']['MSC']['search']);
		$Template->showOnly = specialchars($GLOBALS['TL_LANG']['MSC']['showOnly']);

		// Where
		if (!empty($where))
		{
			$query .= " WHERE ".implode(' AND ',$where);
		}

		// Order by
		$query .= " ORDER BY title ASC";

		// Execute query
		$objLinks = \Database::getInstance()->prepare($query)
											->limit(500)
											->execute($value);

		$arrLinks = array();
		if($objLinks->numRows > 1)
		{
			while($objLinks->next())
			{
				$arrLinks[] = array
				(
					'id'		=> $objLinks->id,
					'pid'		=> $objLinks->pid,
					'title'		=> $objLinks->title,
					'url'		=> $objLinks->url,
					'text'		=> $objLinks->text,
					'ref'       => \Input::get('ref'),
					'icon'		=> $this->getFavicon($objLinks->id)
				);
			}
		}

		$Template->numRows = ($objLinks->numRows) ? $objLinks->numRows . ' Treffer' : 'Not found';
		$Template->links = $arrLinks;
		return $Template->parse();
	}

}