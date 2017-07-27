<?php

namespace Samson\Linksammlung;
 
class Helper extends \Frontend
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
	 * HOOK loadFormField (wird 2x aufgerufen!)
	 * ========================================
	 * Schreibt die Kategorien in das Frontendformular aus dem Formular-Generator
	 *
	 * @param object $objWidget             Enthält das Formular-Objekt. Wird dem Funktionsparameter ein "Widget " davorgesetzt,
	 *                                      dann muß die Klasse bzw. Funktion auch ein Widget sein.
	 * @param string $strForm               Enthält die Formular-ID (Experten-Einstellungen in den Formular-Einstellungen des
	 *                                      Formulargenerators) mit dem Prefix "auto_". Wenn keine Formular-ID angegeben ist,
	 *                                      dann steht hier "auto_form_x" (x = ID des Formulars).
	 * @param object $arrForm
	 */
	public function setCategoriesToForm($objWidget, $strForm, $arrForm)
	{

		//log_message(print_r($arrForm,true),'linksammlung.log');
		//log_message($strForm,'linksammlung.log');
		//log_message(print_r(get_object_vars($objWidget),true),'linksammlung.log');

		// Formularnamen prüfen und nur bei gewünschtem greifen
		if ($strForm == 'auto_linksammlung')
		{
			// Diese Abfrage wird 2x durchlaufen
			//log_message($strForm.' gefunden','linksammlung.log');
			// Auf das korrekte Feld an Hand Name prüfen
			if ($objWidget->name == 'pid')
			{
				// Diese Abfrage wird 1x durchlaufen, wahrscheinlich weil das Objekt beim 2. Durchlauf nicht mehr komplett ist.
				//log_message('pid-Feld gefunden','linksammlung.log');

				$arrTree = self::getSelectTree(); // Kategoriebaum laden
				log_message(print_r($arrTree,true),'linksammlung.log');

				$objWidget->options = $arrTree;

				// Wert auswählen, der vorselektiert wird (selected)
				$objWidget->__set('value', '4');
			}
		}
		
		// Widget Objekt wieder zurück geben
		return $objWidget; 
	}

	/**
	 * Funktion getCategoriesTree
	 * ==========================
	 * Generiert eine Baumstruktur aller Kategorien
	 *
	 * @param -                  Erwartet keine Parameter
	 *
	 * @return array             Array im Format: Array[ID der übergeordneten Kategorie][ID dieser Kategorie] = Name dieser Kategorie
	 */
	public function getCategoriesTree()
	{
		// Kategorien laden
		$objCats = \Database::getInstance()->prepare('SELECT id,pid,title FROM tl_linkscollection WHERE published = ? ORDER BY title ASC')
		                                   ->execute(1);
		// Kategoriebaum sichern
		$arrTree = array();
		if($objCats->numRows > 1)
		{
			// Datensätze speichern
			while($objCats->next()) 
			{
				$arrTree[$objCats->pid][$objCats->id] = $objCats->title;
			}
		}

		return $arrTree; // Baum zurückgeben
	}


	/**
	 * Funktion getSelectTree
	 * ==========================
	 * Generiert ein Options-Array für das select-Feld eines Formulars
	 *
	 * @param -                  Keine
	 *
	 * @return array             Zweidimensionales Array im Format (Beispiel mit 2 Kategorien):
	 *                           Array
	 *                           (
	 *                               [0] => Array
	 *                                      (
	 *                                          [value] => Kategorie-ID
	 *                                          [label] => Kategorie-Name
	 *                                      )
	 *                               [1] => Array
	 *                                      (
	 *                                          [value] => Kategorie-ID
	 *                                          [label] => Kategorie-Name
	 *                                      )
	 *                           ) 
	 */
	public function getSelectTree()
	{
		$arrTree = self::getCategoriesTree(); // Kategoriebaum laden

		$returnArr = array();

		// Optionen der 1. Ebene durchlaufen
		foreach($arrTree[0] as $ebene1_key => $ebene1_value)
		{
			$returnArr[] = array
			(
				'value'       => $ebene1_key,
				'label'       => $ebene1_value
			);
			// Optionen der 2. Ebene durchlaufen, wenn vorhanden
			if($arrTree[$ebene1_key])
			{
				foreach($arrTree[$ebene1_key] as $ebene2_key => $ebene2_value)
				{
					$returnArr[] = array
					(
						'value'       => $ebene2_key,
						'label'       => '- '.$ebene2_value
					);
				}
				// Optionen der 3. Ebene durchlaufen, wenn vorhanden
				if($arrTree[$ebene2_key])
				{
					foreach($arrTree[$ebene2_key] as $ebene3_key => $ebene3_value)
					{
						$returnArr[] = array
						(
							'value'       => $ebene3_key,
							'label'       => '-- '.$ebene3_value
						);
					}
					// Optionen der 4. Ebene durchlaufen, wenn vorhanden
					if($arrTree[$ebene3_key])
					{
						foreach($arrTree[$ebene3_key] as $ebene4_key => $ebene4_value)
						{
							$returnArr[] = array
							(
								'value'       => $ebene4_key,
								'label'       => '--- '.$ebene4_value
							);
						}
						// Optionen der 5. Ebene durchlaufen, wenn vorhanden
						if($arrTree[$ebene4_key])
						{
							foreach($arrTree[$ebene4_key] as $ebene5_key => $ebene5_value)
							{
								$returnArr[] = array
								(
									'value'       => $ebene5_key,
									'label'       => '---- '.$ebene5_value
								);
							}
						}
					}
				}
			}
		}

		return $returnArr;

	}

}
