<!DOCTYPE html>
<html lang="fr">
<head>
    <!-- Title -->
    <meta name="description" content="Home description.">
    <title>Le melomane</title>
    <meta charset="UTF-8"/>
    <!-- Robots -->
    <meta name="robots" content="index, follow">
    <!-- Device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="format-detection" content="telephone=no">
    <!-- Links -->
    <link rel="stylesheet" type="text/css" href="style.css"/>
    <link rel="icon" type="image/png" href="image/note-de-musique.png">
</head>
 <body>
 <section id="hello">
 <header>
 <img src="image/note-de-musique.png" alt ="note de musique" id="logo">
 <h2> Hello le mélomane</h2>
    <div class="boutons">
    <span title="retour pour gérer les artistes"><button type="submit"><a href="index.php">Accueil</a></button></span>
    <span title="ici pour gérer les styles"><button type="submit"><a href="style.php"> les Styles</a></button></span>
</div>
 </header>
    <nav>
        <h1>Genres</h1>
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
    $genre_name = ucfirst($_POST['genre_name']);

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
<div class="center">
<div class="tableau">
<table border>
    <thead>
        <th>Noms des genres</th>
        <th>Supprimer</th>
        <th>Modifier</th>
    </thead>
    <?php
$requete = 'SELECT * FROM `genres` ORDER BY genre_name ASC';
$prepare = $connexion->prepare($requete);
$prepare->execute();
$resultat = $prepare->fetchAll();
foreach ($resultat as $genre) {
    ?>
            <tr>
                <td><?=(htmlentities($genre['genre_name']))?></td>
                <td><button type="submit" ><a href="genre.php?id=<?=$genre['genre_id']?>"><img src="image/supprimer.png" alt ="poubelle" class="picture" ></a></button></td>
                <td><button type="submit" ><a href="genre.php?genre_id=<?=$genre['genre_id']?>"><img src="image/modifier.png" alt ="crayon" class="picture" ></a></button>
                </td>
            </tr>
    <?php
}
?>
</table>
</diV>
<div class="styleformulaire">
<div class="style">
<h3>Ajouter un Genre</h3>
<form method="post" >
        <label>Nom du Genre</label>
        <input type="text" name="genre_name"required>
    <button type="submit" name= "ajouter" value= "ajouter">Ajouter</button>
</form>
</div>
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
        <label>Nom de l'artiste</label>
        <input type="text" name="genre_name" value="<?=(htmlentities($resultat['genre_name'], ENT_QUOTES))?>">
        <input type="hidden" name="genre_id" value="<?=(htmlentities($resultat['genre_id'], ENT_QUOTES))?>">
    <button name="modifier">Enregistrer</button>
</form>
</div>
</div>
</section>
<?php
}
?>
    </main>
    <script src="src/js/app.js"></script>

</body>
</html>



