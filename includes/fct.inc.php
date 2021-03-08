<?php
/**
 * Fonctions pour l'application GSB
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

/**
 * Teste si un quelconque visiteur est connecté
 *
 * @return vrai ou faux (isset)
 */

function estConnecte(){
  return isset($_SESSION['id']);// est ce qu'il y a un id dans la super global session
}

/**
 * Teste si un visiteur est connecté
 *
 * @return vrai ou faux
 */
 function estVisiteurConnecte()
 {
if (estConnecte()){
  return ($_SESSION['statut']=='visiteur');
  }
 }

 /**
 * Teste si un comptable est connecté
 *
 * @return vrai ou faux
 */
 function estComptableConnecte(){
if (estConnecte()){
  return($_SESSION['statut']=='comptable');  
} 
}

 
/**
 * Enregistre dans une variable session les infos d'un visiteur
 *
 * @param String $idUtilisateur ID de l'utilisateur
 * @param String $nom        Nom de l'utilisateur
 * @param String $prenom     Prénom de l'utilisateur
 *
 * @return null
 */
function connecter($id, $nom, $prenom, $statut)
{
   
    $_SESSION['id'] = $id;//variable générale
    $_SESSION['nom'] = $nom;
    $_SESSION['prenom'] = $prenom;
    $_SESSION['statut'] =$statut;
    
}

/**
 * Détruit la session active
 *
 * @return null
 */
function deconnecter()
{
    session_destroy();
}

/**
 * Transforme une date au format français jj/mm/aaaa vers le format anglais
 * aaaa-mm-jj
 *
 * @param String $maDate au format  jj/mm/aaaa
 *
 * @return Date au format anglais aaaa-mm-jj
 */
function dateFrancaisVersAnglais($maDate)
{
    @list($jour, $mois, $annee) = explode('/', $maDate);
    return date('Y-m-d', mktime(0, 0, 0, $mois, $jour, $annee));
}

/**
 * Transforme une date au format format anglais aaaa-mm-jj vers le format
 * français jj/mm/aaaa
 *
 * @param String $maDate au format  aaaa-mm-jj
 *
 * @return Date au format format français jj/mm/aaaa
 */
function dateAnglaisVersFrancais($maDate)
{
    @list($annee, $mois, $jour) = explode('-', $maDate);
    $date = $jour . '/' . $mois . '/' . $annee;
    return $date;
}

/**
 * Retourne le mois au format aaaamm selon le jour dans le mois
 *
 * @param String $date au format  jj/mm/aaaa
 *
 * @return String Mois au format aaaamm
 */
function getMois($date)
{
    @list($jour, $mois, $annee) = explode('/', $date);//chaque fois qu'il ya un "/" il separe les variables
    unset($jour);//retire le jour pour avoir que le mois et l'année
    if (strlen($mois) == 1) {// verifie le nb de caracteres
        $mois = '0' . $mois;// ajoute le "0" au mois
    }
    return $annee . $mois;
}

/* gestion des erreurs */

/**
 * Indique si une valeur est un entier positif ou nul
 * Vérifie si une chaîne fournie est bien numérique entière positive.                    
 *
 * Retrourne true si la valeur transmise $valeur ne contient pas d'autres
 * caractères que des chiffres, false sinon.
 * @param string chaîne à vérifier
 * @param Integer $valeur Valeur
 *
 * @return Boolean vrai ou faux
 */
function estEntierPositif($valeur)
{
    return preg_match('/[^0-9]/', $valeur) == 0;//verifie si il est positif ou non
}

/**
 * Indique si un tableau de valeurs est constitué d'entiers positifs ou nuls
 *
 * @param Array $tabEntiers Un tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function estTableauEntiers($tabEntiers)
{
    $boolReturn = true;
    foreach ($tabEntiers as $unEntier) {
        if (!estEntierPositif($unEntier)) {
            $boolReturn = false;
        }
    }
    return $boolReturn;
}

/**
 * Vérifie si une date est inférieure d'un an à la date actuelle
 *
 * @param String $dateTestee Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateDepassee($dateTestee)
{
    $dateActuelle = date('d/m/Y');
    @list($jour, $mois, $annee) = explode('/', $dateActuelle);
    $annee--;
    $anPasse = $annee . $mois . $jour;
    @list($jourTeste, $moisTeste, $anneeTeste) = explode('/', $dateTestee);
    return ($anneeTeste . $moisTeste . $jourTeste < $anPasse);
}

/**
 * Vérifie la validité du format d'une date française jj/mm/aaaa
 *
 * @param String $date Date à tester
 *
 * @return Boolean vrai ou faux
 */
function estDateValide($date)
{
    $tabDate = explode('/', $date);
    $dateOK = true;
    if (count($tabDate) != 3) {
        $dateOK = false;
    } else {
        if (!estTableauEntiers($tabDate)) {
            $dateOK = false;
        } else {
            if (!checkdate($tabDate[1], $tabDate[0], $tabDate[2])) {
                $dateOK = false;
            }
        }
    }
    return $dateOK;
}

/**
 * Vérifie que le tableau de frais ne contient que des valeurs numériques
 *
 * @param Array $lesFrais Tableau d'entier
 *
 * @return Boolean vrai ou faux
 */
function lesQteFraisValides($lesFrais)
{
    return estTableauEntiers($lesFrais);
}

/**
 * Vérifie la validité des trois arguments : la date, le libellé du frais
 * et le montant
 *
 * Des message d'erreurs sont ajoutés au tableau des erreurs
 *
 * @param String $dateFrais Date des frais
 * @param String $libelle   Libellé des frais
 * @param Float  $montant   Montant des frais
 *
 * @return null
 */
function valideInfosFrais($dateFrais, $libelle, $montant)
{
    if ($dateFrais == '') {
        ajouterErreur('Le champ date ne doit pas être vide');
    } else {
        if (!estDatevalide($dateFrais)) {
            ajouterErreur('Date invalide');
        } else {
            if (estDateDepassee($dateFrais)) {
                ajouterErreur(
                        "date d'enregistrement du frais dépassé, plus de 1 an"
                        );
            }
        }
    }
    if ($libelle == '') {
        ajouterErreur('Le champ description ne peut pas être vide');
    }
    if ($montant == '') {
        ajouterErreur('Le champ montant ne peut pas être vide');
    } elseif (!is_numeric($montant)) {
        ajouterErreur('Le champ montant doit être numérique');
    }
}

/**
 * Ajoute le libellé d'une erreur au tableau des erreurs
 *
 * @param String $msg Libellé de l'erreur
 *
 * @return null
 */
function ajouterErreur($msg)
{
    if (!isset($_REQUEST['erreurs'])) {
        $_REQUEST['erreurs'] = array();
    }
    $_REQUEST['erreurs'][] = $msg;
  }
  
/**
 * Retoune le nombre de lignes du tableau des erreurs
 *
 * @return Integer le nombre d'erreurs
 */
function nbErreurs()
{
    if (!isset($_REQUEST['erreurs'])) {
        return 0;
    } else {
        return count($_REQUEST['erreurs']);
    }
  }
  
/**
* Fonction qui retourne le mois précédent un mois passé en paramètre
*
* @param String $mois Contient le mois à utiliser
*
* @return String le mois d'avant
*/
  function getMoisPrecedent($mois) {
   $numAnnee = substr($mois, 0, 4);
   $numMois = substr($mois, 4, 2);
   if ($numMois == '01') {
       $numMois = '12';
       $numAnnee--;
   } else {
       $numMois--;
   }
   if (strlen($numMois) == 1) {
       $numMois = '0' . $numMois;
   }
   return $numAnnee . $numMois;
  }

 function getLesDouzeMois($mois) {
       $lesMois = array ();
       for ( $i=0 ; $i<=12 ; $i++) {
           $mois = getMoisPrecedent($mois) ;
           $numAnnee = substr($mois, 0, 4);
           $numMois = substr($mois, 4, 2);

      if (strlen($numMois) == 1) { //verifie le nombre de caractere dans le mois
         $numMois = '0' . $numMois;
     }
     $lesMois[] = array(
         'mois' => $numAnnee.$numMois,
         'numAnnee' => $numAnnee,
         'numMois' => $numMois
             );
      }
      return ($lesMois) ;
}
 