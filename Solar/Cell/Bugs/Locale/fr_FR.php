<?php

/**
* 
* Locale file.  Returns the strings for a specific language.
* 
* @category Solar
* 
* @package Solar_Cell
* 
* @subpackage Solar_Cell_Bugs
* 
* @author Jean-Eric Laurent <jel@jelaurent.com>
* 
* @license LGPL
* 
* @version $Id: en_US.php 209 2005-04-16 21:34:10Z pmjones $
* 
*/

return array(
	
	// validation messages
	'VALID_SUMM'      => 'Veuillez entrer un bref r�sum� du rapport.',
	'VALID_TYPE'      => 'Veuillez entrer un type de bug valide.',
	'VALID_QUEUE'     => 'Veuillez s�lectionner une file valide pour le bug.',
	'VALID_STATUS'    => 'Veuillez s�lectionner un statut d\'avancement valide.',
	
	// processing errors
	'ERR_ID'          => 'l\'ID demand� n\'existe pas.',
	
	// report types
	'TYPE_BUG'        => 'Rapport de bug',
	'TYPE_CRITICAL'   => 'Issue Critique',
	'TYPE_EXAMPLE'    => 'Demande pour exemple',
	'TYPE_FEATURE'    => 'Demande pour action',
	
	// status codes
	'STATUS_NEW'       => 'Nouveau',
	'STATUS_CONFIRMED' => 'Confirm�',
	'STATUS_ASSIGNED'  => 'En cours',
	'STATUS_FEEDBACK'  => 'Feedback demand�',
	'STATUS_RESOLVED'  => 'R�solu',
	'STATUS_DUPLICATE' => 'En double',
	'STATUS_BOGUS'     => 'Faux',
	'STATUS_WONTFIX'   => 'Sans suite',
	'STATUS_SUSPENDED' => 'Suspendu',
	'STATUS_REOPENED'  => 'R�-ouvert',
	
	// form labels
	'LABEL_ID'        => 'Report ID',
	'LABEL_TS_NEW'    => 'Premier rapport�',
	'LABEL_TS_MOD'    => 'Dernier modifi�',
	'LABEL_SUMM'      => 'Bref r�sum�',
	'LABEL_TYPE'      => 'Type d\'Etat',
	'LABEL_QUEUE'     => 'File',
	'LABEL_PRIORITY'  => 'Priorit�',
	'LABEL_USER_ID'   => 'Affect� �',
	'LABEL_STATUS'    => 'Etat d\'avancement',
	
);
?>