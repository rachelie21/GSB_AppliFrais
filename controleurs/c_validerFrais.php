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

$mois = getMois(date('d/m/Y'));
$moisPrecedent=getMoisPrecedent($mois);
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);// recupere le contenu de action
switch ($action) {
case 'choisirvisiteur':
   $listevisiteur=$pdo->getListeVisiteur();
   $lesCles = array_keys($listevisiteur);//tableau de clés
   $visiteurASelectionner = $lesCles[0];
   $lesMois=getLesDouzeMois($mois);
   $lesCles1 = array_keys($lesMois);//tableau de clés
   $moisASelectionner = $lesCles[0];
   include  'vues/v_listeVisiteur.php';
   break;
case 'afficheFrais':
   $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
   $listevisiteur= $pdo->getListeVisiteur();
   $visiteurASelectionner= $idVisiteur;
   $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
   $lesMois= getLesDouzeMois($mois);
   $moisASelectionner =$leMois;
   if (!is_array($pdo->getLesInfosFicheFrais($idVisiteur, $leMois))) {
       ajouterErreur('Pas de fiche de frais pour ce visiteur ce mois');
       include 'vues/v_erreurs.php';
       include'vues/v_listeVisiteur.php';
    }else{
        $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
        $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois); 
        $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
        include 'vues/v_afficheFrais.php';
    }
   break; 
case 'corrigerFF':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $lesVisiteurs= $pdo->getListeVisiteur();
    $visiteurASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois= getLesDouzeMois($mois);
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_DEFAULT, FILTER_FORCE_ARRAY);
    $moisASelectionner =$leMois;
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $leMois, $lesFrais);
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
    include 'vues/v_afficheFrais.php';
    break;   
case 'corrigerFHF':
    $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
    $listevisiteur= $pdo->getListeVisiteur();
    $visiteurASelectionner= $idVisiteur;
    $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
    $lesMois= getLesDouzeMois($mois);
    $moisASelectionner =$leMois;
    $leLibelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_STRING);
    $laDate = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $idFrais = filter_input(INPUT_POST, 'frais', FILTER_SANITIZE_NUMBER_INT);
    $leMontant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    //valideInfosFrais($laDate, $leLibelle, $leMontant);
    if (isset ($_POST['corriger'])){
            valideInfosFrais($laDate, $leLibelle, $leMontant);
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        $pdo->MajFraisHorsForfait($idVisiteur,$leMois,$leLibelle,$laDate,$leMontant,$idFrais);
    }
    $lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $leMois);
    $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $leMois);
    $nbJustificatifs = $pdo->getNbjustificatifs($idVisiteur, $leMois);
    include 'vues/v_afficheFrais.php';
    }
    if (isset ($_POST['reporter'])){
    $pdo->majLibelle($idFrais);
    $moisSuivant= getMoisSuivant($leMois);
    if ($pdo->estPremierFraisMois($idVisiteur, $moisSuivant)) {
        $pdo->creeNouvellesLignesFrais($idVisiteur, $moisSuivant);
    }
    $moisAReporter=$pdo->reporterFraisHorsForfait($idFrais,$moisSuivant);
    ?>
    <div class="alert alert-info" role="alert">
        <p>Ce frais hors forfait a bien été reporté au mois suivant!</p>
    </div>
        <?php
    }
    break;
case 'validerFrais':
  $idVisiteur = filter_input(INPUT_POST, 'lstVisiteurs', FILTER_SANITIZE_STRING);
  $listevisiteur= $pdo->getListeVisiteur();
  $visiteurASelectionner= $idVisiteur;
  $leMois = filter_input(INPUT_POST, 'lstMois', FILTER_SANITIZE_STRING);
  $lesMois= getLesDouzeMois($mois);
  $moisASelectionner =$leMois;
  $etat="VA";
  $valideFrais=$pdo->majEtatFicheFrais($idVisiteur, $leMois, $etat);
  $montantFF=$pdo->montantFF($idVisiteur,$leMois);
  $montantHF=$pdo->montantHF($idVisiteur,$leMois);
  $pdo->calculMontantValide($idVisiteur,$leMois,$montantFF,$montantHF);
  ?>
    <div class="alert alert-info" role="alert">
        <p>La fiche a bien été validée!</p>
    </div>
        <?php
        include 'vues/v_listeVisiteur.php';
        break;     
case 'supprimerFrais':
   ?>
   <div class="alert alert-info" role="alert">
       <p><h4>Voulez vous reporter ou supprimer le frais?<br></h4><a
           href="index.php?uc=validerFrais&action=supprimer">Supprimer</a> ou
           <a href="index.php?uc=validerFrais&action=reporter">Reporter</a></p>
   </div>
       <?php
       break;
case 'supprimer':
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_NUMBER_INT);
    $pdo->refuserFraisHorsForfait($idFrais);
    ?>
    <div class="alert alert-info" role="alert">
        <p>Ce frais hors forfait a bien été supprimé!</p>
    </div>
    <?php
    break;
}


