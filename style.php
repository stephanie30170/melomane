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
        <h1>Styles</h1>
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

<table border>
    <thead>
        <th>styles</th>
        <th>genres</th>
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

foreach($stylegenre as $key => $value){
    ?> 
    <tr>
    <td><?=$value['style_name']?></td>
    <td><?=$value['genre_name']?></td>
    <td><?=$value['genre_id']?></td>
    <td><button type="submit" ><a href="style.php?supprimer=<?=$value['style_id']?>">Supprimer</a></button>
        <button type="submit" ><a href="style.php?style_id=<?=$value['style_id']?>">Modifier</a></button>
                </td>
                </tr>
<?php } 
?>
<button type="submit"><a href="index.php">RETOUR</a></button>

<h3>Ajouter un Style en bas du tableau ci-dessous</h3>
<form method="POST" >
    <table>
        <tr>
        <td>Nom du Style</td>
        <td><input type="text" name="style_name"></td>
        <td> Choisir un genre</td>
        <td><select name="choix">
        <?php foreach($genre as $value){?>
        <option value="<?=$value['genre_id']?>"><?=$value['genre_name']?></option>
        <?php } ?></td>
        </select>
        </tr>
    </table>
    <button type="submit" name= "ajouter" value= "ajouter">Ajouter</button>
</form>
<?php 

    if (isset($_POST['ajouter'])) {
        $post_name = $_POST['style_name'];
        $post_genre_id = $_POST['choix'];
        
        $requete = "INSERT INTO `styles` (`style_name`, `style_genre_id`)
                    VALUES (:style_name, :style_genre_id);";
        $prepare = $connexion->prepare($requete);
        $prepare->execute(array(
            ':style_name' => $post_name,
            ':style_genre_id' => $post_genre_id  
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
<form action="style.php" method="post" >
        <td>modifier le style ici</td>
        <td><input type="text" name="style_name" value="<?=$resultat['style_name']?>"></td>
        <input type="hidden" name="style_id" value="<?=$resultat['style_id']?>">
        <input type="hidden" name="style_genre_id" value="<?=$resultat['style_genre_id']?>">
        <button name="modifier">Enregistrer</button>
</form>
<?php
}
?>
</main>
</body>
</html>