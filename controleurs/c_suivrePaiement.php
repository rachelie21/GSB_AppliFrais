<?php
/**
 * Suivre le paiement
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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING); // récupère le contenu de action
$mois = getMois(date('d/m/Y'));
$idComptable = $_SESSION['id'];
switch ($action) {
case 'choisirvisiteur':
    $listevisiteur=$pdo->getListeVisiteur();
    $lesCles = array_keys($listevisiteur);//tableau de clés
    $visiteurASelectionner = $lesCles[0];
    $lesMois = getLesDouzeMois($mois);
    //$lesMois= $pdo->getLesMoisVA();
    $lesCles1 = array_keys($lesMois);//tableau de clés
    $moisASelectionner = $lesCles[0];
    include  'vues/v_listeVisiteur.php';
    break;
case 'afficheFrais':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $listevisiteur = $pdo->getListeVisiteur();
    $visiteurASelectionner = $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = getLesDouzeMois($mois);
    $moisASelectionner = $leMois;
    if (!is_array($pdo->getLesInfosFicheFrais($idVisiteur, $leMois))) {
        ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
        include 'vues/v_erreurs.php';
        include'vues/v_listeVisiteur.php';
    }else{
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois); 
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
        $libEtat = $lesInfosFicheFrais['libEtat'];
        $montantValide = $lesInfosFicheFrais['montantValide'];
        $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
        //$numAnnee = substr($leMois, 0, 4);
        //$numMois = substr($leMois, 4, 2);
        include 'vues/v_paiement.php';
    }
    break;
case 'mettreEnPaiement':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs = $pdo->getLesVisiteurs();
    $visiteurASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois = getLesMois($mois);
    $moisASelectionner = $leMois;
    $etat="RB";
    $pdo->majEtatFicheFrais($idVisiteur, $leMois, $etat);
     ?>
    <div class="alert alert-info" role="alert">
    <p>La fiche a bien été mise en paiement!</p>
    </div>
        <?php
        include 'vues/v_listeVisiteur.php';
        break;

    include 'vues/v_validefrais.php';
    break;
}