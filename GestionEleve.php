<?php

include_once("Menu.php");
echo'<section>
        </br></br>';
session_start();

//session_destroy();

function affichTab($tab) {
    foreach ($tab as $cle => $valeur) {
        '<tr>';
        if (is_array($valeur)) {
            echo "<input type='hidden', name= 'eleveID[]' value=" . $cle . ">";
            echo '<td>' . $cle . '<td>';
            affichTab($valeur);
        } else {
            echo "<td><input type='text', name='elevModi[]' placeholder=" . $cle . " ></td>";
            echo "<td><input type='text', name='elevModi2[]' placeholder=" . $valeur . "></td>";
        }
        echo'</tr>';
    }
}

//session_destroy();// REMETTRA $SESSIONCHOIX à0// VERIFIER TOUT $SESSION UNSET
require("connect.php");
echo'<h2>Gestion des Eleves</h2>';
if (!isset($_POST['eleve']) && !isset($_POST['nombre']) && !isset($_SESSION['eleve'])) {
    ?>
    <p>Souhaitez vous</p>
    <form action="GestionEleve.php" method="post">
        <input type="radio" name="eleve" value="ajout"> Ajouter un ou des élève(s)<br>
        <input type="radio" name="eleve" value="supprime">Supprimer un ou des élève(s)<br>
        <input type="radio" name="eleve" value="modifie">Mettre à jour les informations d'un ou de plusieurs élève(s)<br>
        <input type="submit" value ="Valider"> </form>

    <?php

}
if (isset($_POST ['eleve']) || isset($_SESSION['eleve'])) {
    if (isset($_POST['eleve'])) {//a supp?
        $_SESSION['eleve'] = $_POST['eleve'];
    }

    if ($_SESSION['eleve'] == 'ajout' && !isset($_POST ['nombre']) && !isset($_SESSION['nombre'])) {
        echo'<form action="GestionEleve.php" method="post">';
        echo'Combien d\'ajouts souhaitez vous effectuer? <input type="number" name="nombre" min="1" required><br>';
        echo'<input type="submit" value ="Valider"> </form>';
        echo '<input type="hidden" name="eleve" value="ajout"'; // A supprimer?
    } elseif ($_SESSION['eleve'] == 'ajout' && isset($_POST['nombre'])) {
        $_SESSION['nombre'] = $_POST['nombre'];
        $i = $_POST['nombre'];
        echo '<p>Vous souhaitez ajouter ' . $i . ' élèves. Remplissez les champs ci-dessous:</p>';
        echo'<form action="GestionEleve.php" method="post">';
        for ($c = 0; $c < $i; $c++) {
            echo'<p>Eleve ' . ($c + 1) . '<br>';
            echo 'Nom: <input type="text" name="Nom[]" required><br>';
            echo 'Prénom: <input type="text" name="Prenom[]" required><br></p>';
        }
        echo'<input type= "submit" value= "Envoyer">';
        echo '</form>';
    } elseif ($_SESSION['eleve'] == 'ajout' && isset($_SESSION['nombre'])) {
        $Nom = $_POST['Nom'];
        $Prenom = $_POST['Prenom'];
        echo "Vous avez ajouté : <br>";

        $lg = count($Nom);
        for ($c = 0; $c < $lg; $c++) {
            // ajout de l'étudiant dans la table "personnel"
            $ajout = 'INSERT INTO personnel (IDPersonnel, Nom, Prenom, DateDeNaissance, MotDePasse, Informations, Promotion, GroupeTD)VALUES ("","' . $Nom[$c] . '","' . $Prenom[$c] . '", "", "","", "", "")';
            $req2 = mysqli_query($BDD, $ajout)or die('Erreur SQL !<br>' . $ajout . '<br>' . mysql_error());
        }
        // recherche de son ID en vu de l'ajout dans la table "est"
        echo "<table>
                       <tr>
              <th>Nom</th>
              <th>Prenom </th>
              <th>Nouvel ID </th>
              </tr>";
        for ($c = 0; $c < $lg; $c++) {
            $rechercheIDPersonnel = "SELECT IDPersonnel FROM personnel WHERE Nom='$Nom[$c]'";
            $req3 = mysqli_query($BDD, $rechercheIDPersonnel) or die('Erreur SQL !<br>' . $rechercheIDPersonnel . '<br>' . mysql_error());
            $data2 = mysqli_fetch_array($req3);
            echo '<tr><td>' . $Nom[$c] . '</td><td>' . $Prenom[$c] . '</td>';
            echo '<td>' . $data2['IDPersonnel'] . '</td></tr>';
            // insertion de l'élève et du type élève dans la table est
            $ajout2 = 'INSERT INTO est (DateHabilitation,IDType,IDPersonnel) VALUES ("GETDATE()","3","' . $data2['IDPersonnel'] . '")';
            $req5 = mysqli_query($BDD, $ajout2)or die('Erreur SQL !<br>' . $ajout2 . '<br>' . mysql_error());
        }
        echo'</table>';
        unset($_SESSION['eleve']);
        unset($_SESSION['nombre']);
        echo '<a href="GestionEleve.php">Retour à la gestion</a>';
    } elseif (isset($_SESSION['eleve']) && !isset($_POST['elevSupp']) && !isset($_SESSION['CHECK'])) {// mettre dans la meme 
        $affichEleve = "SELECT Nom, Prenom, personnel.IDPersonnel FROM typepersonne, est, personnel WHERE typepersonne.IDType=est.IDType AND est.IDPersonnel=personnel.IDPersonnel AND est.IDType=3 ORDER BY Nom; ";
        $req4 = mysqli_query($BDD, $affichEleve);

        if ($_SESSION['eleve'] == 'supprime' && !isset($_POST['elevSupp'])) {
            echo '<p>Cochez les élèves à supprimer :</p>';
            echo '<form action="GestionEleve.php" method="post">'; // supprimer les posts ici et mettre après
        } else {
            echo '<p>Cochez les élèves à modifier :</p>';
            echo '<form action="GestionEleve.php" method="post">'; // supprimer les posts ici et mettre après
        }

        echo "<table>
                       <tr>
              <th></th>
              <th>Identifiant</th>
              <th>Nom </th>
              <th>Prenom </th>
              </tr>";
        while ($data3 = mysqli_fetch_array($req4)) {
            echo "<tr><td><input type='checkbox', name='elevSupp[]' value=" . $data3['IDPersonnel'] . "></td>";
            echo "<td>" . $data3['IDPersonnel'] . "</td>";
            echo "<td>" . $data3['Nom'] . "</td>";
            echo "<td>" . $data3['Prenom'] . "</td></tr>";
        }
        echo'</table><input type ="submit" value="Valider"/>';
        echo '</form>';
        $_SESSION['CHECK'] = 1;
    } elseif (isset($_POST['elevSupp']) && $_SESSION['eleve'] == 'supprime') {
        $EleveSupp = $_POST['elevSupp'];
        $lg2 = count($EleveSupp);
        echo ' Vous avez supprimé :' . $lg2 . ' élève(s): <br>';

        echo "<table>
              <tr>
              <th>ID</th>
              <th>Nom </th>
              <th>Prenom </th>
              </tr>";
        for ($c = 0; $c < $lg2; $c++) {
            $EleveASupprimer = "SELECT IDPersonnel, Nom, Prenom FROM personnel WHERE IDPersonnel='$EleveSupp[$c]'";
            $req6 = mysqli_query($BDD, $EleveASupprimer)or die('Erreur SQL !<br>' . $EleveASupprimer . '<br>' . mysql_error());
            $data4 = mysqli_fetch_array($req6);
            $suppression = "DELETE FROM est WHERE IDPersonnel='$EleveSupp[$c]'";
            $suppression2 = "DELETE FROM personnel WHERE Nom='" . $data4['Nom'] . "'";
            $req7 = mysqli_query($BDD, $suppression)or die('Erreur SQL!<br>' . $suppression . '<br>' . mysql_error());
            $req8 = mysqli_query($BDD, $suppression2)or die('Erreur SQL!<br>' . $suppression2 . '<br>' . mysql_error());
            echo '<tr><td>' . $data4['IDPersonnel'] . "</td><td>" . $data4['Nom'] . "</td><td> " . $data4['Prenom'] . '</td><tr>';
        }
        echo'</table>';
        unset($_SESSION['eleve']);
        unset($_SESSION['CHECK']);
        echo '<a href="GestionEleve.php">Retour à la gestion</a>';
    } elseif ($_SESSION['eleve'] == 'modifie') {
        if (!isset($_POST['elevModi'])) {
            $EleveMod = $_POST['elevSupp'];
            $_SESSION['nb'] = $lg3 = count($EleveMod);
            echo ' Vous allez modifier ' . $lg3 . ' élève(s).<br> ';
            echo 'Effectuez les changements souhaités :<br>';
            for ($c = 0; $c < $lg3; $c++) {
                $EleveModo = "SELECT IDPersonnel ,Nom, Prenom FROM personnel WHERE IDPersonnel='$EleveMod[$c]'";
                $req6 = mysqli_query($BDD, $EleveModo)or die('Erreur SQL !<br>' . $EleveModo . '<br>' . mysql_error());
                $data4 = mysqli_fetch_array($req6);
                $elevStock[$data4['IDPersonnel']] = array($data4['Nom'] => $data4['Prenom']);
                $_SESSION['data5[]'][$c] = array($data4['IDPersonnel'], $data4['Nom'], $data4['Prenom']);
                //$session data 5= $eleveStock??
            }


// ACTION MODIFIE.PHP => GESTION /!\ Rajouter une condition
            echo '<form action="GestionEleve.php" method="post">';
            echo "<table>
                       <tr>
              <th>ID</th>
              <th>Nom </th>
              <th>Prenom </th>
              </tr>";
            affichTab($elevStock);
            echo'</table><input type ="submit" value="Valider"/>';
            echo '</form><br>';
        } else {
            $nouveauNom = $_POST["elevModi"];
            $nouveauPre = $_POST['elevModi2'];
            $idEleve = $_POST['eleveID'];
            $nochange = $_SESSION['data5[]'];
            echo "Vos changements ont bien été enregistrés. Voici les nouvelles données :<br>";


            echo "<table>
              <tr>
              <th>ID</th>
              <th>Nom </th>
              <th>Prenom </th>
              </tr>";

            for ($c = 0; $c < $_SESSION['nb']; $c++) {
//utilisez requete eleveModo hors 1ere boucle pour afficher finalement les changements

                $update = "UPDATE Personnel SET Nom=";
                ($nouveauNom[$c] != "") ? $update.= "'$nouveauNom[$c]'" : $update.="'" . $nochange[$c][1] . "'";
                $update.=", Prenom=";
                ($nouveauPre[$c] != "") ? $update.="'$nouveauPre[$c]'" : $update.="'" . $nochange[$c][2] . "'";
                $update.=" WHERE IDPersonnel=" . "'" . $nochange[$c][0] . "'";
                $res = mysqli_query($BDD, $update);

                echo "<tr><td>" . $nochange[$c][0] . '</td>';
                echo "<td>" . ($nouveauNom[$c] != "" ? $nouveauNom[$c] . ' ' : $nochange[$c][1] . '  ') . "</td>";
                echo "<td>" . ($nouveauPre[$c] != "" ? $nouveauPre[$c] . '<br>' : $nochange[$c][2] . '<br>') . "<td><tr/>";
            }
            echo'</table><br>';
            echo '<a href="GestionEleve.php">Retour à la gestion</a>';
            unset($_SESSION['data5[]']);
            unset($_SESSION['nb']);
            unset($_SESSION['eleve']);
            unset($_SESSION['CHECK']);
        }
    }
}
include_once 'Footer.php';
?>
