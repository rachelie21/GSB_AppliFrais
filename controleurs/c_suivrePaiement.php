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

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$idComptable = $_SESSION['id'];
switch ($action) {
case 'choisirvisiteur':
    
    include 'vues/v_validefrais.php';
    break;
}