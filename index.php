<?php
/**
 * Index du projet GSB
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Rachel Kott
 * @author    <kottrachel@gmail.com>
 * @author   Beth Sefer
 * @copyright 2020-2021
 */

//require erreur fatale include ca s arrete pas le programme
require_once 'includes/fct.inc.php';//FICHIER INTEGRE et fct c est des fonctions sans requetes sql
require_once 'includes/class.pdogsb.inc.php';//classe pdogsb fonction avec requetes sql

session_start();// session=variable pouvant contenir plusieurs variables de types differents(variable super global)
$pdo = PdoGsb::getPdoGsb();//appel de la methode de la classe getpdogsb
$estConnecte= estConnecte();//appel de la methode de la classe fct.inc,verifie si un client est connecté ds la session et on rentre le return de la fonction
$estVisiteurConnecte= estVisiteurConnecte();
$estComptableConnecte= estComptableConnecte();
require 'vues/v_entete.php';//require ca lance et si ca marche pas erreur fatale le programme s arrete
$uc = filter_input(INPUT_GET, 'uc', FILTER_SANITIZE_STRING);//filter input verifier le contenu de la variable uc
if ($uc && !$estComptableConnecte&&!$estVisiteurConnecte) {//si vide
    $uc = 'connexion';
} elseif (empty($uc)) {//si uc a recu un resultat  
    $uc = 'accueil';
}
switch ($uc) {
case 'connexion':
    include 'controleurs/c_connexion.php';
    break;
case 'accueil':
    include 'controleurs/c_accueil.php';
    break;
case 'gererFrais':
    include 'controleurs/c_gererFrais.php';
    break;
case 'etatFrais':
    include 'controleurs/c_etatFrais.php';
    break;
case 'validerFrais':
    include 'controleurs/c_validerFrais.php';
    break;
case 'deconnexion':
    include 'controleurs/c_deconnexion.php';
    break;
case 'suivrePaiement':
    include 'controleurs/c_suivrePaiement.php';
    break;
}
require 'vues/v_pied.php';
