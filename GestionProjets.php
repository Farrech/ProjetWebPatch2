    <?php
    include_once("Menu.php");
echo'<section>
        </br></br>';
    session_start();
    //session_destroy();
    require("connect.php");
    echo'<h2>Gestion des Projets</h2>';

    if (!isset($_POST['projet']) && !isset($_SESSION['choix'])) {
        echo '
        <p>Souhaitez vous</p>
        <form action="GestionProjets.php" method="post">
            <input type="radio" name="projet" value="ajout"> Ajouter un ou des projet(s)<br>
            <input type="radio" name="projet" value="supprime">Supprimer un ou plusieurs projet(s)<br>
            <input type="radio" name="projet" value="modifie">Mettre à jour les informations d\'un ou de plusieurs projet(s)<br>
            <input type="submit" value ="Valider"> </form>';
    }
    if (isset($_POST['projet']) || isset($_SESSION['choix'])) {
        if (isset($_POST['projet'])) {
            $_SESSION['choix'] = $_POST['projet'];
        }
        if ($_SESSION['choix'] == "ajout" && !isset($_POST ['nombre']) && !isset($_SESSION['nombre'])) {
            echo'<form action="GestionProjets.php" method="post">';
            echo'Combien d\'ajouts souhaitez vous effectuer? <input type="number" name="nombre" min="1" required><br>';
            echo'<input type="submit" value ="Valider"> </form>';
        }
        if ($_SESSION['choix'] == "ajout" && isset($_POST['nombre']) && !isset($_SESSION['nombre'])) {
            $_SESSION['nombre'] = $_POST['nombre'];
            $i = $_POST['nombre'];
            $modExistant = "SELECT NomModule FROM module";
            echo '<p>Vous souhaitez ajouter ' . $i . ' projets. Remplissez les champs ci-dessous:</p>';
            echo'<form action="GestionProjets.php" method="post">';
            for ($c = 0; $c < $i; $c++) {
                echo'<p>Projet ' . ($c + 1) . '<br>';
                echo 'Intitulé: <input type="text" name="intitule[]" required><br>';
                echo 'Informations: <input type="textarea" name="informations[]" required><br></p>';
                echo 'Module : <select name ="attachMod[]", id="attachMod"><br>';
                $req15 = mysqli_query($BDD, $modExistant);
                while ($data8 = mysqli_fetch_array($req15)) {
                    echo $data8['NomModule'];
                    echo '<option value ="' . $data8['NomModule'] . '">' . $data8['NomModule'] . '</option>'; //recupérerIDMOD
                }
                echo"</select><br>";
            }
            echo'<input type= "submit" value= "Envoyer">';
            echo '</form>';
        } elseif ($_SESSION['choix'] == "ajout" && isset($_SESSION['nombre'])) {
            $lg = $_SESSION['nombre'];
            for ($i = 0; $i < $lg; $i++) {
                $recupIDMod = 'SELECT IDModule FROM module WHERE NomModule="' . $_POST['attachMod'][$i] . '"';
                $req16 = mysqli_query($BDD, $recupIDMod);
                $data9 = mysqli_fetch_array($req16);

                $insertion = 'INSERT INTO projet (IDProjet, Intitule, Informations, IDModule) VALUES ("","' . $_POST['intitule'][$i] . '","' . $_POST['informations'][$i] . '","' . $data9['IDModule'] . '" )';

                mysqli_query($BDD, $insertion);
            }
            echo '<a href="GestionProjets.php">Retour à la gestion</a>';
            unset($_SESSION['choix']);
            unset($_SESSION['nombre']);
        } elseif (!isset($_SESSION['Proj']) && $_SESSION['choix'] != "ajout") {
            if ($_SESSION['choix'] == 'supprime'&&!isset($_SESSION['projet'])) {
                echo '<p>Cochez les projets à supprimer :</p>';
                echo '<form action="GestionProjets.php" method="post">'; // supprimer les posts ici et mettre après
            } else {
                echo '<p>Cochez les projets à modifier :</p>';
                echo '<form action="GestionProjets.php" method="post">'; // supprimer les posts ici et mettre après
            }
            $affichProjet = "SELECT IDProjet, Intitule, Informations, IDModule FROM projet ORDER BY Intitule";
            $req17 = mysqli_query($BDD, $affichProjet);

            echo "<table>
                       <tr>
              <th></th>
              <th>ID Projet</th>
              <th>Intitulé </th>
              <th>Informations </th>
              <th> Module </th>
              </tr>";
            while ($data10 = mysqli_fetch_array($req17)) {
                echo "<tr><td><input type='checkbox', name='Projet[]' value=" . $data10['IDProjet'] . "></td>";
                echo "<td>" . $data10['IDProjet'] . "</td>";
                echo "<td>" . $data10['Intitule'] . "</td>";
                echo "<td>" . $data10['Informations'] . "</td>";
                echo "<td>" . $data10['IDModule'] . "</td></tr>";
            }
            echo'</table><input type ="submit" value="Valider"/>';
            echo '</form>';
            $_SESSION['Proj'] = 1;
        } elseif ($_SESSION['choix'] == "supprime" && isset($_POST['Projet'])) {
            $lg = count($_POST['Projet']);
            $projetSupp = $_POST['Projet'];
            echo ' Vous avez supprimé :' . $lg . ' projet(s): <br>';

            echo "<table>
              <tr>
              <th>ID Projet</th>
              <th>Intitule </th>
              <th>Informations </th>
              <th>Module </th>
              </tr>";
            for ($c = 0; $c < $lg; $c++) {
                $ProjetASupprimer = "SELECT IDProjet, Intitule, Informations, IDModule FROM projet WHERE IDProjet='$projetSupp[$c]'";
                $req18 = mysqli_query($BDD, $ProjetASupprimer)or die('Erreur SQL !<br>' . $ProjetASupprimer . '<br>' . mysql_error());
                $data11 = mysqli_fetch_array($req18);
                echo '<tr><td>' . $data11['IDProjet'] . "</td><td>" . $data11['Intitule'] . "</td><td> " . $data11['Informations'] . "</td><td> " . $data11['IDModule'] . '</td><tr>';
                $suppression = "DELETE FROM projet WHERE IDProjet='$projetSupp[$c]'";
                mysqli_query($BDD, $suppression);
            }
            echo'</table>';
            echo '<a href="GestionProjets.php">Retour à la gestion</a>';
            unset($_SESSION['choix']);
            unset($_SESSION['Proj']);
            
        } elseif (!isset($_SESSION['check'])&& $_SESSION['choix']=="modifie") {
            echo 'Effectuez vos modifications';
            $ProjModi = $_POST['Projet'];
            $_SESSION['nb'] = $lg = count($_POST['Projet']);
            $affichToutModule = "SELECT NomModule, IDModule FROM module";

            echo '<form action="GestionProjets.php" method="post">';
            echo "<table>
                       <tr>
              <th>ID Projet</th>
              <th>Intitule </th>
              <th>Informations </th>
              <th>Module </th>
              </tr>";
            for ($c = 0; $c < $lg; $c++) {
                $ProjMod = "SELECT IDProjet, Intitule, Informations,IDModule FROM projet WHERE IDProjet='" . $ProjModi[$c] . "'";
                $req19 = mysqli_query($BDD, $ProjMod);
                $_SESSION['sauvegardeInit'[$c]] = $data12 = mysqli_fetch_array($req19);
                echo '<tr><td> ' . $data12['IDProjet'] . '</td><td>';
                echo '<input type= "text" name="newIntitule[]", placeholder= ' . $data12['Intitule'] . '></td><td>';
                echo '<input type= "text" name="newInformations[]", placeholder= ' . $data12['Informations'] . '></td><td>';
                $req20 = mysqli_query($BDD, $affichToutModule);
                echo '<select name= "newModule[]", id ="newModule" >';
                while ($data13 = mysqli_fetch_array($req20)) {
                    echo '<option value ="' . $data13['IDModule'] . '"';
                    if ($data13['IDModule'] == $data12['IDModule']) {
                        echo 'selected="selected"';
                    }
                    echo '>' . $data13['NomModule'] . '</option>';
                }
                echo'</select>';
                echo'</td></tr>';
            }
            echo'</table><input type ="submit" value="Valider"/></form>';
            $_SESSION['check2']=1;
            $_SESSION['check'] = 1;
        } elseif(isset ($_SESSION['check2'])){
            $newIntitule = $_POST['newIntitule'];
            $newInformations = $_POST['newInformations'];
            $newModule = $_POST['newModule'];
            $lg = $_SESSION['nb'];

            for ($c = 0; $c < $lg; $c++) {


                $update = "UPDATE projet SET Intitule=";
                ($newIntitule[$c] != "") ? $update.= "'$newIntitule[$c]'" : $update.="'" . $_SESSION['sauvegardeInit'[$c]][1] . "'";
                $update.=", Informations=";
                ($newInformations[$c] != "") ? $update.="'$newInformations[$c]'" : $update.="'" . $_SESSION['sauvegardeInit'[$c]][2] . "'";
                $update.=", IDModule=";
                ($newModule[$c] != $_SESSION['sauvegardeInit'[$c]][3]) ? $update.="'$newModule[$c]'" : $update.="'" . $_SESSION['sauvegardeInit'[$c]][3] . "'";
                $update.=" WHERE IDProjet=" . "'" . $_SESSION['sauvegardeInit'[$c]][0] . "'";
                $res = mysqli_query($BDD, $update);
                echo "Vos données ont bien été modifiées";

                echo '<a href="GestionProjets.php">Retour à la gestion</a>';
                unset($_SESSION['Proj']);
                unset($_SESSION['choix']);
                unset($_SESSION['nb']);
                unset($_SESSION['check2']);
                unset($_SESSION['check']);
                unset ($_SESSION['sauvegardeInit']);
            }
        }

        
    }
        include_once 'Footer.php';
    ?>


