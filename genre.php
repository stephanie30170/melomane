<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Title -->
    <meta name="description" content="Home description.">
    <title>le melomane</title>
    <meta charset="UTF-8"/>
    <!-- Robots -->
    <meta name="robots" content="index, follow">
    <!-- Device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <!-- Links -->
    <link rel="stylesheet" type="css" href="style.css"/>
</head>
 <body>
    <nav>
        <h1>Genre</h1>
        <div id="btn_menu"></div>
    </nav>
    <main>
    <?php
include "connectDB.php";
try {
    // instancie un objet $connexion à partir de la classe PDO
    $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
} catch (PDOException $e) {
    // en cas d'erreur, on récup et on affiche, grâce à notre try/catch
    exit("❌🙀💀 OOPS :\n" . $e->getMessage());

}
//Ajouter un nouveau genre
if (isset($_POST['ajouter'])) {
    $genre_name = $_POST['genre_name'];
    
    $requete = "INSERT INTO `genres` (`genre_name`)
                VALUES (:genre_name);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':genre_name' => $genre_name,

    ));
}
//modifier un genre
if (isset($_POST['modifier'])) {
    $genre_id = $_POST['genre_id'];
    $genre_name = $_POST['genre_name'];

    $requete = "UPDATE `genres` SET `genre_name` =:genre_name WHERE `genre_id` =:genre_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':genre_id' => $genre_id,
        ':genre_name' => $genre_name,
    ));
}
//supprimer un genre:
if (isset($_GET['id'])) {
    $genre_id = $_GET['id'];
    $requete = "DELETE FROM `genres` WHERE `genre_id`=:genre_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':genre_id' => $genre_id,
    ));
}
?>
<table border>
    <thead>
        <th>genre id</th>
        <th>genre name</th>
    </thead>
    <?php   
$requete = 'SELECT * FROM `genres` ';
$prepare = $connexion->prepare($requete);
$prepare->execute();
$resultat = $prepare->fetchAll();
foreach ($resultat as $genre) {
    ?>
            <tr>
                <td><?=$genre['genre_id']?></td>
                <td><?=$genre['genre_name']?></td>
                <td>
                <button type="submit" ><a href="genre.php?id=<?=$genre['genre_id']?>">Supprimer</a></button>
                <button type="submit" ><a href="genre.php?genre_id=<?=$genre['genre_id']?>">Modifier</a></button>
                </td>
            </tr>
    <?php
}
?>
</table>
<button type="submit"><a href="index.php">RETOUR</a></button>
<h3>Ajouter un Genre</h3>
<form method="post" >
    <table>
        <tr>
        <td>Nom du Genre</td>
        <td><input type="text" name="genre_name"></td>
        </tr>
    </table>
    <button type="submit" name= "ajouter" value= "ajouter">Ajouter</button>
</form>
<?php
if (isset($_GET['genre_id'])) {
    $requete = 'SELECT * FROM `genres` WHERE `genre_id` =:genre_id;';
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ":genre_id" => $_GET['genre_id'],
    ));
    $resultat = $prepare->fetch();
    ?>
 </br>
<h3>Modifier un genre</h3>
<form action="genre.php" method="post" >
    <table>
        <tr>
        <td>Nom de l'artiste</td>
        <td><input type="text" name="genre_name" value="<?=$resultat['genre_name']?>"></td>
        </tr>
        <input type="hidden" name="genre_id" value="<?=$resultat['genre_id']?>">
    </table>
    <button name="modifier">Enregistrer</button>
</form>
<?php
}
?>
    </main>
    <script src="src/js/app.js"></script>

</body>
</html>



