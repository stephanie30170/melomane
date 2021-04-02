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
    <link rel="stylesheet" type="text/css" href="src/css/style.css"/>
</head>
 <body>
    <nav>
        <h1>Cameron Findlay</h1>
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
//requetes de selection
$requete = "SELECT * FROM `artiste`";
$prepare = $connexion->prepare($requete);
$prepare->execute();
$artiste = $prepare->fetchAll();

$requete ="SELECT * FROM `styles`";
$prepare = $connexion->prepare($requete);
$prepare->execute();
$assostyles = $prepare->fetchAll();

//Ajouter un nouvel artiste 
if (isset($_POST['ajouter'])) {
    $artiste_name = $_POST['artiste_name'];
    
    $requete = "INSERT INTO `artiste` (`artiste_name`)
                VALUES (:artiste_name);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':artiste_name' => $artiste_name,
    ));
}
//modifier un Artiste
if (isset($_POST['modifier'])) {
    $artiste_id = $_POST['artiste_id'];
    $artiste_name = $_POST['artiste_name'];

    $requete = "UPDATE `artiste` SET `artiste_name` =:artiste_name WHERE `artiste_id` =:artiste_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':artiste_id' => $artiste_id,
        ':artiste_name' => $artiste_name,
    ));
}
//supprimer un Artiste :
if (isset($_GET['id'])) {
    $artiste_id = $_GET['id'];
    $requete = "DELETE FROM `artiste` WHERE `artiste_id`=:artiste_id;";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':artiste_id' => $artiste_id,
    ));
}
?>
<table border>
    <thead>
        <th>artiste</th>
        <th>style</th>
        <th>genre </th>
        <th>Artiste : Modifier ou supprimer</th>
    </thead>
    <?php   
;
//requete de READ ALL
$requete = 'SELECT artiste_id, artiste_name, style_name, style_id, genre_name FROM assoc_styles_artiste JOIN artiste ON artiste_id=assoc_a_id JOIN styles on style_id=assoc_s_id JOIN genres ON genre_id = style_genre_id;';
$prepare = $connexion->prepare($requete);
$prepare->execute();
$assoc = $prepare->fetchAll();
foreach($assoc as $key => $value){
     ?>
            <tr>
                <td><?=$value['artiste_name']?></td>
                <td><?=$value['style_name']?></td>
                <td><?=$value['genre_name']?></td>
            <td>
            <button type="submit" ><a href="index.php?id=<?=$value['artiste_id']?>">Supprimer</a></button>
            <button type="submit" ><a href="index.php?artiste_id=<?=$value['artiste_id']?>">Modifier</a></button>
            </td>
            </tr>
    <?php
}
?>
</table>
<button type="submit"><a href="style.php">Pour gerer les Styles</a></button>
<button type="submit"><a href="genre.php">Pour gerer les Genres</a></button>

<h3>Ajouter un artiste</h3>
<form method="post" >
    <table>
        <tr>
        <td>Nom de l'artiste</td>
        <td><input type="text" name="artiste_name"></td>
        </tr>
    </table>
    <button type="submit" name= "ajouter" value= "ajouter">Ajouter</button>
</form>

<h3>Liste des artistes</h3>
<form method="post" >
<select name="assoc_artiste_id">
        <option> -- Choisissez un artiste svp -- </option>
        <?php foreach ( $artiste as $artist): ?>
        <option value=<?= $artist['artiste_id'] ?>><?= $artist['artiste_name'] ?></option>
        <?php endforeach; ?>
</select>
<!-- liste des styles  -->
<h3>rajouter ou modifier Style : </h3>
<select name="assoc_style_id">
        <option> -- Choisissez un style svp -- </option>
        <?php foreach ( $assostyles as $listestyle): ?>
        <option value=<?= $listestyle['style_id'] ?>><?= $listestyle['style_name'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name= "associer" value= "associer">Associer</button>
    
</form>
<?php
if (isset($_POST['associer'])) {
    $post_style_id = $_POST['assoc_style_id'];
    $post_artiste_id = $_POST['assoc_artiste_id'];
    
    $requete = "INSERT INTO `assoc_styles_artiste` (`assoc_S_id`, `assoc_a_id`)
                VALUES (:assoc_s_id, :assoc_a_id);";
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ':assoc_s_id' => $post_style_id,
        ':assoc_a_id' => $post_artiste_id
        
    ));
   
}

if (isset($_GET['artiste_id'])) {
    $requete = 'SELECT * FROM `artiste` WHERE `artiste_id` =:artiste_id;';
    $prepare = $connexion->prepare($requete);
    $prepare->execute(array(
        ":artiste_id" => $_GET['artiste_id'],
    ));
    $resultat = $prepare->fetch();
    ?>
 </br>
<h3>Modifier un Artiste</h3>
<form action="index.php" method="post" >
    <table>
        <tr>
        <td>Nom de l'artiste</td>
        <td><input type="text" name="artiste_name" value="<?=$resultat['artiste_name']?>"></td>
        </tr>
        <input type="hidden" name="artiste_id" value="<?=$resultat['artiste_id']?>">
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

