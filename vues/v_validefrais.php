<?php
/**
 * Vue valider les frais
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
?>
 <div class="row">
    <div class="col-md-4">
        <h3>Sélectionner un visiteur : </h3>
        <form action="index.php?uc=validerFrais&action=choisirvisiteur" 
              method="post" role="form">
            <div class="form-group">
                <label for="lstVisiteur" accesskey="n">Visiteur : </label>
                <select id="lstVisiteur" name="lstVisiteur" class="form-control">
                    <?php
                    foreach ($listevisiteur as $uneliste) {
                        $numvisiteur = $uneliste['id'];
                        $prenom= $uneliste['prenom'];
                        $nom = $uneliste['nom'];
                        if ($idvisiteur != $visiteurAselectionner) {
                            ?>
                    <option selected value="<?php echo $numvisiteur?>">
                        <?php echo $nom .'   ' .$prenom?> </option> -->
                            <?php
                            
                        } else {
                            ?>    
                    <option value="<?php echo $numvisiteur?>">
                        <?php echo $nom .'   ' .$prenom?> </option> -->
                            <?php
                            
                        }
                        
                        }
           ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <p><strong>Mois : </strong> </p>
                <label for="lstMois" accesskey="n"></label>
                <select id="lstMois" name="lstMois" class="form-control">


                    <?php
                    foreach ($lesMois as $unMois) {
                        $mois = $unMois['mois'];
                        $numAnnee = $unMois['numAnnee'];
                        $numMois = $unMois['numMois'];
                        if ($mois == $moisASelectionner) {
                            ?>
                    <option selected value="<?php echo $mois ?>">
                        <?php echo $numMois . '/' . $numAnnee ?> </option>

                            <?php
                            
                        } else {
                            ?>
                    <option value="<?php echo $mois ?>">
                        <?php echo $numMois . '/' . $numAnnee ?> </option>
                            <?php
                            
                        }
                        
                        }
                        ?>
                </select>
                <br>
                <input id="ok" type="submit" value="Valider" class="btn btn-success"
                       role="button">
            </div>
            
        </form>
        </div>
    </div>
    