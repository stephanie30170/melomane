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
 <span title="ici pour gérer les genres"><button type="submit"><a href="genre.php">les Genres</a></button></span>
</div>
 </header>
    <nav>
        <h1 id="haut">Styles</h1>
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
//modifier un style
if (isset($_POST['modifier'])) {
    $style_id = $_POST['style_id'];
    $style_name = $_POST['style_name'];
    $style_genre_id = $_POST['style_genre_id'];

    $requete = "UPDATE `styles` SET `style_name` =:style_name, `style_genre_id` =:style_genre_id WHERE `style_id` =:style_id ;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':style_id' => $style_id,
        ':style_name' => $style_name,
        ':style_genre_id' => $style_genre_id,

    ));
}
//supprimer un style :
if (isset($_GET['supprimer'])) {
    $style_id = $_GET['supprimer'];
    $requete = "DELETE FROM `styles` WHERE `style_id` =:style_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':style_id' => $style_id,
    ));
}?>
<div class="center">
<div class="tableau">
<span title="pour descendre en bas du tableau">
<button name="bas"><a href="#bas"><img src="image/bas.png" alt ="fleche vers le bas" class="picture" ></a></button>
</span>
<table border>
    <thead>
        <th>Styles</th>
        <th>Genres</th>
        <th>Supprimer</th>
        <th>Modifier</th>
    </thead>
<?php
//requete selection
$requete = "SELECT * FROM `genres`";
$prepare = $connexion->prepare($requete);
$prepare->execute();
$genre = $prepare->fetchAll();

$requete = 'SELECT * FROM styles
                    JOIN genres ON style_genre_id = genre_id
                    ORDER BY styles.style_name ASC;';
// autre requete possible
//$requete = "SELECT style_name, style_id, genre_name, genre_id FROM `styles`
// LEFT OUTER JOIN `genres` ON `genre_id` = `style_genre_id` ;";
$prepare = $connexion->prepare($requete);
$prepare->execute();
$stylegenre = $prepare->fetchAll();

foreach ($stylegenre as $key => $value) {
    ?>
    <tr>
    <td><?=(htmlentities($value['style_name'], ENT_QUOTES))?></td>
    <td><?=(htmlentities($value['genre_name'], ENT_QUOTES))?></td>
    <td><button type="submit" ><a href="style.php?supprimer=<?=$value['style_id']?>"><img src="image/supprimer.png" alt ="poubelle" class="picture" ></a></button></td>
        <td><button type="submit" ><a href="style.php?style_id=<?=$value['style_id']?>"><img src="image/modifier.png" alt ="crayon" class="picture" ></a></button>
                </td>
                </tr>
<?php }
?>

</table>
<span title="pour remonter en haut du tableau">
<button id="bas"><a href="#haut"><img src="image/haut.png" alt ="fleche vers le haut" class="picture" ></a></button>
</span>
</div>
<div class="styleformulaire">
<div class="style">
<h3>Ajouter un Style </h3>
<form method="POST" >
    <label>Nom du Style</label>
    <input type="text" name="style_name" required></td>
        <select name="choix" required>
        <option value=""> -- Choisissez un genre svp -- </option>
        <?php foreach ($genre as $value) {?>
        <option value="<?=(htmlentities($value['genre_id'], ENT_QUOTES))?>"><?=(htmlentities($value['genre_name'], ENT_QUOTES))?></option>
        <?php }?>
        </select>
    <button type="submit" name= "ajouter" value= "ajouter">Ajouter</button>
</form>
</diV>
<?php

if (isset($_POST['ajouter'])) {
    $post_name = ucfirst( $_POST['style_name']);
    $post_genre_id = $_POST['choix'];

    $requete = "INSERT INTO `styles` (`style_name`, `style_genre_id`)
                    VALUES (:style_name, :style_genre_id);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':style_name' => $post_name,
        ':style_genre_id' => $post_genre_id,
    ));
}
if (isset($_GET['style_id'])) {
    $requete = 'SELECT * FROM `styles` WHERE `style_id` =:style_id;';
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ":style_id" => $_GET['style_id'],
    ));
    $resultat = $prepare->fetch();
    ?>
<div class="stylemodifier">
<h3>Modifier un style</h3>
<form action="style.php" method="post" >
        <label>Nouveau nom du style</label>
        <input type="text" name="style_name" value="<?=(htmlentities($resultat['style_name'], ENT_QUOTES))?>">
        <input type="hidden" name="style_id" value="<?=(htmlentities($resultat['style_id'], ENT_QUOTES))?>">
        <input type="hidden" name="style_genre_id" value="<?=(htmlentities($resultat['style_genre_id'], ENT_QUOTES))?>">
        <button name="modifier">Enregistrer</button>
</form>
</div>
</div>
</section>
<?php
}
?>
</main>
</body>
</html>