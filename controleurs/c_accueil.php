<?php
/**
 * Gestion de l'accueil
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
$estVisiteurConnecte = estVisiteurConnecte();
$estComptableConnecte = estComptableConnecte();

if ($estVisiteurConnecte) {
include
   'vues/v_accueil_visiteur.php';
}
 elseif
   ($estComptableConnecte){
    include 'vues/v_accueil_comptable.php';
} else {
   include 'vues/v_connexion.php';
}