
    <?php
    include_once("Menu.php");
    echo'<section>
        </br></br>';
    if(!isset($_SESSION)){
    session_start();
    }
    require("connect.php");

    if (!isset($_POST['identifiant']) && !isset($_POST['inscription'])) {

        echo'<p> Connectez vous <p/>
        <form method="post" action="Connexion.php"/>
        <p>
            Votre identifiant: <input type="text" name="identifiant" required/><br>
            Votre mot de passe <input type="password" name="password" required /><br>
            <input type="submit" value="Valider"/> </p>
            </form>
            <form method="post" action="Connexion.php"/
            <p> Pas de compte? Inscrivez vous</p>
            <input id="S\'inscrire" type="submit" name="inscription" value="S\'inscrire">
            </form>';
    } elseif (isset($_POST['identifiant'])) {
        $password = $_POST['password'];
        $identifiant = $_POST['identifiant'];
        $sql = "SELECT MotDePasse, IDPersonnel FROM personnel WHERE Nom='$identifiant'";
        $req = mysqli_query($BDD, $sql) or die('Erreur SQL !<br>' . $sql . '<br>' . mysql_error());
        $data = mysqli_fetch_array($req);

        if ($data['MotDePasse'] != $password) {
            echo '<p>Mauvais login / password. Merci de recommencer</p>';
            $_POST['identifiant'] = NULL; // Test 
            include('Connexion.php');
            exit;
        } else {
            $_SESSION['identifiant'] = $identifiant;
            $req9 = "SELECT IDType FROM est, Personnel WHERE personnel.IDPersonnel=est.IDPersonnel AND personnel.IDPersonnel='" . $data['IDPersonnel'] . "'";
            $res9 = mysqli_query($BDD, $req9);
            $recupID = mysqli_fetch_array($res9);
            $req10 = "SELECT NomType, IDType FROM typePersonne WHERE IDType='" . $recupID['IDType'] . "'";
            $res10 = mysqli_query($BDD, $req10);
            $recupType = mysqli_fetch_array($res10);
            echo 'Bonjour ' . $identifiant . '<br>';
            echo 'Vous etes bien logué en tant que ' . $recupType['NomType'] . '<br>';
            if ($recupType['IDType'] == 1) {
                echo'<a href="GestionEleve.php">Gestion des élèves</a><br>';
                echo'<a href="GestionProjets">Gestion des projets</a><br>';
                echo'<a href="GestionModule.php">Gestion des modules</a><br>';
            }
        }
    } elseif (isset($_POST['inscription'])) {
        
    function random($car) {
        $code = "";
        $codeSecret = "abcdefghijklmnopqrstuvwxyz0123456789";
        srand((double) microtime() * 1000000);
        for ($i = 0; $i < $car; $i++) {
            $code.= $codeSecret[rand() % strlen($codeSecret)];
        }
        return $code;
    }

    }
    include_once 'Footer.php';
    ?>