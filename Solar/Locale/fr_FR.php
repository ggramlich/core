<?php
/**
 * 
 * Locale file.  Returns the strings for a specific language.
 * 
 * @category Solar
 * 
 * @package Solar_Locale
 * 
 * @author Jean-Eric Laurent <jel@jelaurent.com>
 * 
 * @license LGPL
 * 
 * @version $Id$
 * 
 */
return array(
    
    // formatting codes and information
    'FORMAT_LANGUAGE'    => 'Fran�ais',
    'FORMAT_COUNTRY'     => 'France',
    'FORMAT_CURRENCY'    => 'EUR �%s', // printf()
    'FORMAT_DATE'        => '%j %m %Y', // strftime(): 19 Mar 2005
    'FORMAT_TIME'        => '%r', // strftime: 24-hour 
    
    // operation actions
    'SUBMIT_SAVE'            => 'Sauvegarder',
    'SUBMIT_PREVIEW'         => 'Previsualisation',
    'SUBMIT_CANCEL'          => 'Annuler',
    'SUBMIT_DELETE'          => 'Effacer',
    'SUBMIT_RESET'           => 'R�initialiser',
    'SUBMIT_NEXT'            => 'Prochain',
    'SUBMIT_PREVIOUS'        => 'Pr�c�dent',
    'SUBMIT_SEARCH'          => 'Chercher',
    'SUBMIT_GO'              => 'Action!',
    'SUBMIT_LOGIN'         => 'Sign In',
    'SUBMIT_LOGOUT'        => 'Sign Out',
    
    // error messages
    'ERR_FILE_NOT_FOUND'       => 'Impossible de trouver le fichier.',
    'ERR_FILE_NOT_READABLE'    => 'Impossible de lire le fichier.',
    'ERR_EXTENSION_NOT_LOADED' => 'Extension non charg�e.',
    'ERR_CONNECTION_FAILED'    => 'Connection invalide.',
    'ERR_INVALID'              => 'Donn�e invalide.',
    
    // validation messages
    'VALID_ALPHA'        => 'Veuillez utiliser esclusivement les lettres A-Z.',
    'VALID_ALNUM'        => 'Veuillez utiliser esclusivement les lettres (A-Z) et les nombres (0-9)',
    'VALID_BLANK'        => 'Cette valeur n\'est pas autoris�e.',
    'VALID_EMAIL'        => 'Veuillez entrer une adresse email valide.',
    'VALID_INKEYS'       => 'Veuillez choisir une valeur diff�rente.',
    'VALID_INLIST'       => 'Veuillez choisir une valeur diff�rente.',
    'VALID_INSCOPE'      => 'Cette valeur n\'appartient pas � la cible d�finie.',
    'VALID_INTEGER'      => 'Veuillez exclusivement utiliser des nombres entiers.',
    'VALID_ISODATE'      => 'Veuillez entrer une date au format "yyyy-mm-dd".',
    'VALID_ISODATETIME'  => 'Veuillez entrer une date-temps au format "yyyy-mm-ddThh:mm:ss".',
    'VALID_ISOTIME'      => 'Veuillez entrer un temps au format "hh:mm:ss".',
    'VALID_MAX'          => 'Veuillez entrer une valeur plus petite.',
    'VALID_MAXLENGTH'    => 'Veuillez entrer un texte moins long.',
    'VALID_MIN'          => 'Veuillez entrer une plus grande valeur.',
    'VALID_MINLENGTH'    => 'Veuillez entrer un texte plus long.',
    'VALID_NONZERO'      => 'Cette valeur ne peut pas �tre z�ro.',
    'VALID_NOTBLANK'     => 'Cette valeur doit �tre laiss�e en blanc.',
    'VALID_URI'          => 'Veuiilez entrer une adresse web valide.',
    
    // success/failure messages
    'SUCCESS_SAVED'           => 'Sauvegard�.',
    'FAILURE_FORM'                 => 'Merci de corriger les erreurs affich�es.',
    
    // generic text
    'TEXT_AUTH_USERNAME' => 'Identifi� comme',
    
    // generic labels
    'LABEL_HANDLE'     => 'Identifiant',
    'LABEL_PASSWD'     => 'Mot de passe',
);
?>