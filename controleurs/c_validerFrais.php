<?php
/**
 * Gestion de la validation des frais
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idComptable = $_SESSION['id'];
switch ($action) {
case 'choisirvisiteur':
    $listevisiteur=$pdo->getListeVisiteur();
    $clesvisiteur = array_keys($listevisiteur);
    $visiteurASelectioner = $clesvisiteur[0];   
    $mois = getMois(date('d/m/Y')); 
    $lesMois = $pdo->getLesDouzeMois($mois);
    $lesCles = array_keys($lesMois);
    $moisASelectionner = $lesCles[0];
    include 'vues/v_validefrais.php';
    break;
}