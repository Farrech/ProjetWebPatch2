    <?php
    include_once("Menu.php");
echo'<section>
        </br></br>';
    //S'occuper DES TXTAREA // V2RIFIER UnSET
    session_start();

    //session_destroy();

    function affichTab2($tab) {
        foreach ($tab as $cle => $valeur) {
            '<tr>';
            if (is_array($valeur)) {
                echo "<input type='hidden', name= 'eleveID[]' value=" . $cle . ">";
                echo '<td>' . $cle . '<td>';
                affichTab2($valeur);
            } else {
                echo "<td><input type='text', name='elevModi[]' placeholder=" . $cle . " ></td>";
                echo "<td><input type='textarea', name='elevModi2[]' <textarea placeholder=" . $valeur . "></textarea></td>";
            }
            echo'</tr>';
        }
    }

    require("connect.php");
    echo'<h2>Gestion des Modules</h2>';
    if (!isset($_POST['modu']) && !isset($_SESSION['choix'])) {
        echo'<p>Souhaitez vous</p>
        <form action="GestionModule.php" method="post">
        <input type="radio" name="modu" value="ajout">Ajouter un ou des modules(s)<br>
            <input type="radio" name="modu" value="supprime">Supprimer un ou des modules(s)<br>
            <input type="radio" name="modu" value="modifie">Mettre à jour les informations d\'un ou de plusieurs module(s)<br>
            <input type="submit" value ="Valider"> </form>';
    }

    if (isset($_POST['modu']) || isset($_SESSION['choix'])) {
        if (isset($_POST['modu'])) {
            $_SESSION['choix'] = $_POST['modu'];
        }

        if ($_SESSION['choix'] == 'ajout') {

            if (!isset($_POST['nombre']) && !isset($_SESSION['nombre'])) {
                echo'<form action="GestionModule.php" method="post">';
                echo'Combien d\'ajouts souhaitez vous effectuer? <input type="number" name="nombre" min="1" required><br>';
                echo'<input type="submit" value ="Valider"> </form>';
            } elseif (isset($_POST['nombre']) && !isset($_POST["intitule"])) {
                $_SESSION['nombre'] = $_POST['nombre'];
                $i = $_POST['nombre'];
                echo '<p>Vous souhaitez ajouter ' . $i . ' modules. Remplissez les champs ci-dessous:</p>';
                echo'<form action="GestionModule.php" method="post">';
                for ($c = 0; $c < $i; $c++) {
                    echo'<p>Module ' . ($c + 1) . '<br>';
                    echo 'Intitulé: <input type="text" name="intitule[]" required><br>';
                    echo 'Informations: <input type="textarea" name="informations[]" required><br></p>';
                }
                echo'<input type= "submit" value= "Envoyer">';
                echo '</form>';
            } elseif (isset($_POST['intitule'])) {
                echo "Vous avez ajouté : <br>";
                $intitule = $_POST['intitule'];
                $informations = $_POST['informations'];  // pourquoi ça affiche que les 5 premiers??
                $_SESSION['nb']=$lg = count($intitule);
                echo "<table>
              <tr>
              <th>ID</th>
              <th>Intitulé </th>
              <th>Informations </th>
              </tr>";
                for ($c = 0; $c < $lg; $c++) {
                    // ajout de l'étudiant dans la table "personnel"
                    $ajout3 = 'INSERT INTO module (IDModule, NomModule, Informations)VALUES ("","' . $intitule[$c] . '","' . $informations[$c] . '")';
                    $req2 = mysqli_query($BDD, $ajout3)or die('Erreur SQL !<br>' . $ajout3 . '<br>' . mysql_error());
                    $select = "SELECT IDModule, NomModule, Informations FROM module WHERE NomModule='" . $intitule[$c] . "'";
                    $req11 = mysqli_query($BDD, $select);
                    $selection = mysqli_fetch_array($req11);
                    echo '<tr><td>' . $selection['IDModule'] . '</td><td>' . $selection['NomModule'] . '</td><td>' . $selection['Informations'] . '</td></tr>';
                }
                echo'</table>';
                unset($_SESSION['choix']);
                unset($_SESSION['nombre']);
                echo '<a href="GestionModule.php">Retour à la gestion</a>';
            }
        } elseif (isset($_SESSION['choix']) && !isset($_POST['Module'])&&!isset ($_SESSION['check'])) {
            $affichModule = "SELECT IDModule, NomModule, Informations FROM module ORDER BY NomModule";
            $req12 = mysqli_query($BDD, $affichModule);
            if ($_SESSION['choix'] == 'supprime') {
                echo '<p>Cochez les modules à supprimer :</p>';
            } else {
                echo '<p>Cochez les modules à modifier :</p>';
            }
            echo '<form action="GestionModule.php" method="post">';

            echo "<table>
        <tr>
        <th></th>
        <th>ID Module</th>
        <th>Intitule </th>
        <th>Informations </th>
        </tr>";
            while ($data5 = mysqli_fetch_array($req12)) {
                echo "<tr><td><input type='checkbox', name='Module[]' value=" . $data5['IDModule'] . "></td>";
                echo "<td>" . $data5['IDModule'] . "</td>";
                echo "<td>" . $data5['NomModule'] . "</td>";
                echo "<td>" . $data5['Informations'] . "</td></tr>";
            }
            echo'</table><input type ="submit" value="Valider"/>';
            echo '</form>';
        } elseif (isset($_POST['Module']) && $_SESSION['choix'] == 'supprime') {
            $ModSupp = $_POST['Module'];
            $lg2 = count($ModSupp);
            echo ' Vous avez supprimé :' . $lg2 . ' module(s): <br>';

            echo "<table>
              <tr>
              <th>ID</th>
              <th>Nom </th>
              <th>Prenom </th>
              </tr>";
            for ($c = 0; $c < $lg2; $c++) {
                $ModuleASupprimer = "SELECT IDModule, NomModule, Informations FROM module WHERE IDModule='$ModSupp[$c]'";
                $req13 = mysqli_query($BDD, $ModuleASupprimer)or die('Erreur SQL !<br>' . $ModuleASupprimer . '<br>' . mysql_error());
                $data6 = mysqli_fetch_array($req13);
                echo '<tr><td>' . $data6['IDModule'] . "</td><td>" . $data6['NomModule'] . "</td><td> " . $data6['Informations'] . '</td><tr>';
                $suppression = "DELETE FROM module WHERE IDModule='$ModSupp[$c]'";
                mysqli_query($BDD, $suppression);
            }
            echo'</table>';
            unset($_SESSION['choix']);
            unset($_SESSION['nombre']);
            echo '<a href="GestionModule.php">Retour à la gestion</a>';
        } elseif (isset($_POST['Module']) && $_SESSION['choix'] == 'modifie') {
            $_SESSION['check']=1;
            echo 'Effectuez vos modifications';
            $ModMod = $_POST['Module'];
            $_SESSION['nb']=$lg3 = count($ModMod);

            for ($c = 0; $c < $lg3; $c++) {
                $ModuleModo = "SELECT IDModule, NomModule, Informations FROM module WHERE IDModule=" . $ModMod[$c] . "";
                $req14 = mysqli_query($BDD, $ModuleModo);
                $data7 = mysqli_fetch_array($req14);
                $modStock[$data7['IDModule']] = array($data7['NomModule'] => $data7['Informations']);
                $_SESSION['data7[]'][$c] = array($data7['IDModule'], $data7['NomModule'], $data7['Informations']);
            }
            echo '<form action="GestionModule.php" method="post">';
            echo "<table>
                       <tr>
              <th>ID</th>
              <th>Intitule </th>
              <th>Informations </th>
              </tr>";
            affichTab2($modStock);
            echo'</table><input type ="submit" value="Valider"/>';
            echo '</form><br>';
        } else {
            $nouveauInti = $_POST["elevModi"];
            $nouveauInfo = $_POST['elevModi2'];
            $idModu= $_POST['eleveID'];
            $nochange = $_SESSION['data7[]'];
            echo "Vos changements ont bien été enregistrés. Voici les nouvelles données :<br>";


            echo "<table>
              <tr>
              <th>ID</th>
              <th>Nom </th>
              <th>Informations </th>
              </tr>";

            for ($c = 0; $c < $_SESSION['nb']; $c++) {
//utilisez requete eleveModo hors 1ere boucle pour afficher finalement les changements

                $update = "UPDATE module SET NomModule=";
                ($nouveauInti[$c] != "") ? $update.= "'$nouveauInti[$c]'" : $update.="'" . $nochange[$c][1] . "'";
                $update.=", Informations=";
                ($nouveauInfo[$c] != "") ? $update.="'$nouveauInfo[$c]'" : $update.="'" . $nochange[$c][2] . "'";
                $update.=" WHERE IDModule=" . "'" . $nochange[$c][0] . "'";
                $res = mysqli_query($BDD, $update);

                echo "<tr><td>" . $nochange[$c][0] . '</td>';
                echo "<td>" . ($nouveauInti[$c] != "" ? $nouveauInti[$c] . ' ' : $nochange[$c][1] . '  ') . "</td>";
                echo "<td>" . ($nouveauInfo[$c] != "" ? $nouveauInfo[$c] . '<br>' : $nochange[$c][2] . '<br>') . "<td><tr/>";
            }
            echo'</table><br>';
            echo '<a href="GestionModule.php">Retour à la gestion</a>';
            unset($_SESSION['data7[]']);
            unset($_SESSION['nb']);
            unset($_SESSION['choix']);
            unset($_SESSION['check']);
        }
    }
        include_once 'Footer.php';
    ?>