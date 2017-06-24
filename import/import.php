<?php

// Kategorien einlesen
$temp = file('export_kategorien.txt');
foreach($temp as $row)
{
	$col = explode('|', $row);
	$katid[] = $col[0];
	$katname[] = $col[1];
	$katbeschreibung[] = $col[2];
	$katsortierung[] = $col[3];
	$katposition[] = $col[4];
	$katelternkategorie[] = $col[5];
	$katlinks[] = $col[6];
	$katneuelinks[] = $col[7];
	$kataktiv[] = $col[8];
}

// Alphabetisch aufsteigend sortieren
array_multisort($katsortierung, SORT_ASC, $katname, $katid, $katbeschreibung, $katposition, $katelternkategorie, $katlinks, $katneuelinks, $kataktiv);

// Neue ID's zuordnen und Sortierung vorbereiten für Datenbank
$id = 1;
foreach($katid as $key => $value)
{
	$katdbid[$value] = $id;
	$katdbsorting[$value] = 64; // Startwert, wird nach Benutzung später mit 64 addiert
	$id++;
}
$katdbsorting[-1] = 64;

// SQL-Import erstellen
$fp = fopen('tl_linkscollection.sql', 'w');
for($x=0;$x<count($katid);$x++)
{
	fputs($fp, 'INSERT INTO tl_linkscollection (id, pid, sorting, title, published) VALUES (');
	fputs($fp, $katdbid[$katid[$x]] . ', ');
	if($katelternkategorie[$x] == '-1') fputs($fp, '0, ');
	else fputs($fp, $katdbid[$katelternkategorie[$x]] . ', ');
	fputs($fp, $katdbsorting[$katelternkategorie[$x]] . ', ');
	$katdbsorting[$katelternkategorie[$x]] += 64;
	fputs($fp, "'" . addslashes($katname[$x]) . "', ");
	fputs($fp, '1);');
	fputs($fp, "\n");
}
fclose($fp);

return;

echo "<pre>";
print_r($katdbid);
echo "</pre>";

?>
