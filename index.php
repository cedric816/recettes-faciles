<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recettes Faciles</title>
</head>
<body>
    <?php
        include ('conf-bdd.php');

        try{
            //Créer des composants d'accès aux données. 
            $connexion = new PDO(DB_DRIVER . ":host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_LOGIN, DB_PASS, DB_OPTIONS);
            echo('connexion BDD ok');

            //Créer une requête de sélection pour requêter des données issues de la table "recettes".
            $requete = "SELECT * FROM `recettes`";
            $prepare = $connexion->prepare($requete);
            $prepare->execute();
            echo("<h1>Liste des recettes:</h1>");
            while ($donnee = $prepare->fetch()){
                echo("<li>".$donnee['recette_titre'].":</li><p>".$donnee['recette_contenu']."</p>");
            }

            //Créer une requête d'insertion qui ajoute une nouvelle recette de votre choix dans la table "recettes".
            $requete = "INSERT INTO `recettes` (`recette_titre`, `recette_contenu`, `recette_datetime`)
                        VALUES(:titre, :contenu, :datejour)";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':titre'=> 'spaghetti bolo',
                ':contenu' => 'Faire cuire des spaghetti fins dans l\'eau bouillante; égoutter, metttre la bolo et du parmesan',
                ':datejour' => date('Y-m-d-H-i-s')
            ));

            //Créer une requête de modification qui modifie votre nouvelle recette, ajoutée à l'étape précédente, et qui ajoute l'émoji de votre choix au début du titre. Par exemple on modifie le titre "ma pâte à pizza maison" par "😺 ma pâte à pizza maison".
            $requete = "UPDATE `recettes` SET `recette_contenu` = :contenu WHERE `recette_titre` = :titre";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':contenu' => '🍝Faire cuire des spaghetti fins dans l\'eau bouillante; égoutter, metttre la bolo et du parmesan',
                ':titre' => 'spaghetti bolo'
            ));

            //Créer une requête de suppression qui supprime une entrée de la table "recettes".
            //d'abord supprimer les associations de la clé étrangère 'assoc_hr_recette_id'=1
            $requete = "DELETE FROM `assoc_hashtags_recettes` WHERE `assoc_hr_recette_id` = :id";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':id' => 1
            ));
            //ensuite on peut supprimer l'entrée de la table 'recettes' qui a un id=1
            $requete = "DELETE FROM `recettes` WHERE `recette_id` = :id";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':id' => 1
            ));

            //Créer une requête qui ajoute l'entrée "levain" dans la table "hashtags".
            $requete = "INSERT INTO `hashtags`(`hashtag_nom`) VALUES(:nom)";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':nom' => 'levain'
            ));
            $lastInsertedHashtagId = $connexion->lastInsertId();

            //je remets la recette du pain au levain supprimée ci-avant :)
            $requete = "INSERT INTO `recettes`(`recette_titre`, `recette_contenu`, `recette_datetime`)
            VALUES(:titre, :contenu, :datejour)";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':titre' => 'pain au levain',
                ':contenu' => '## Ingrédients\r\n\r\nListe des ingrédients pour faire un pain d\'environ 700g après cuisson. Il est possible de diviser ce gros pain en plusieurs pains moyens, voir plus bas.\r\n\r\n- [Levain liquide actif](levain-express-tuto.html) nourri la veille : 100g\r\n- Eau 40°C tiède sans chlore : 350g\r\n- Sel : 11g\r\n- Farine T65 : 500g\r\n\r\nOn peut aussi mélanger les types de farines, par exemple utiliser 400g de T65 avec 100g de T110. C\'est selon votre goût. Et pour accroître la production on peut facilement multiplier ces quantités et travailler une pâte à pain géante. Quand vos premiers tests seront concluants, par exemple.\r\n\r\n## Matériel\r\n\r\nPour faire du pain on va avoir besoin de tout ça :\r\n\r\n- une pièce ni trop froide (plus de 21°C) ni trop chaude (inférieure à 27°C) \r\n- un grand saladier\r\n- un fouet ou une fourchette\r\n- une cocotte en fonte\r\n- un petit bol contenant une moitié d\'eau sans chlore\r\n- un petit saladier et un torchon propre par pain\r\n- un four\r\n- idéalement : un coupe-pâte\r\n\r\n## Autolyse\r\n\r\nSouvent recommandée, l\'autolyse permet de détendre le gluten et agir sur la texture, la ténacité de la pâte. Je n\'ai encore pu prendre le temps de réaliser des expériences pour voir si de réels changements opéraient avec ou sans autolyse. Les tests que j\'ai pu faire n\'étaient pas trop plaisant parce que l\'ajout du levain après autolyse est assez délicat à la main, ça en met partout, ça colle. Bref, voici en tout cas comment faire une autolyse.\r\n\r\n- Faire tiédir les 350g d\'eau à 40°C max\r\n- Dans le saladier verser l\'eau et ajouter progressivement les 500g de farine tout en mélangeant\r\n- Laisser reposer 30, 45 voir 60 minutes minimum à température ambiante. On peut aussi laisser reposer une nuit ou une journée complète.\r\n\r\n## Préparation\r\n\r\n**Si vous n\'avez pas fait d\'autolyse :**\r\n- Faire tiédir les 350g d\'eau à 40°C max\r\n- Dans le saladier verser l\'eau, les 11g de sel et les 100g de levain\r\n- Bien battre tout ça\r\n- Ajouter progressivement les 500g de farine tout en mélangeant. À la fin, pour bien mélanger on utilise directement ses mains propres\r\n- Se mouiller les mains dans le petit bol contenant une moitié d\'eau sans chlore et essayer de bouler un peu la pâte dans le saladier. On peut en profiter pour décrocher les morceaux de farine collées au paroi.\r\n\r\n**Si vous avez fait une autolyse :**\r\n- Ajouter les 100g de levain et bien mélanger, attention ça colle grave\r\n- Laisser reposer 30-40 minutes\r\n- Ajouter les 11g de sel et pétrir brièvement\r\n- Se mouiller les mains dans le petit bol contenant une moitié d\'eau sans chlore et essayer de bouler un peu la pâte dans le saladier.\r\n\r\nVoilà on a notre pâte à pain. On va laisser le levain - composé de sa centaine de milliards de petites mains - travailler. C\'est la première phase de la fermentation.\r\n\r\n## Première phase de fermentation\r\n\r\nCouvrir et placer le saladier contenant votre pâte dans votre pièce à température ambiante (21°C minimum). Eviter de poser le saladier sur une surface trop froide (carrelage, pierre, etc), ça pourrait grandement retarder la fermentation. Pour cette première phase de la fermentation, enclencher deux minuteurs. Le premier minuteur (3-4 heures) c\'est le temps global de la première fermentation. Le deuxième minuteur (30 minutes) correspond aux 4 séries de rabats qui donneront une belle texture à la mie de votre pain.\r\n\r\n- Couvrir le saladier et le placer dans une pièce ni trop froide (plus de 21°C) ni trop chaude (inférieure à 27°) pendant 3-4 heures, selon la température.\r\n- Toutes les 4 premières demi-heures, faire une série de rabats. Les mains mouillées, on prend la pâte à une extrémité on l\'étire et on vient la rabattre sur le pâton, puis on recommence l\'opération trois fois, depuis les autres extrémités\r\n\r\n## Division & façonnage\r\n\r\nC\'est le moment de diviser votre gros pain en plusieurs pains moyens et de leur donner une belle forme.\r\n\r\n- Fariner un plan de travail et y verser la pâte\r\n- À l\'aide d\'un coupe-pâte ou d\'un couteau diviser la pâte en autant de pains voulus\r\n- Laisser reposer la pâte 15 minutes\r\n- Pendant ce temps placer un torchon propre dans un petit saladier, par pain préparé. Bien fariner le torchon. C\'est ce qu\'on appelle un banneton.\r\n- Bouler chaque pain par le dessous, sans l\'écraser.\r\n- Placer chaque pain dans son banneton, soudures vers le haut. Vérifier que les soudures soient bien... soudées.\r\n- Couvrir chaque banneton\r\n\r\n## Deuxième phase de fermentation\r\n\r\nLa fermentation en deux étapes, c\'est donner le temps suffisant au levain et son armée d\'ouvriers pour travailler toute la pâte\r\n\r\n- Placer chaque banneton dans votre pièce à bonne température\r\n- Enclencher le minuteur sur 3-6 heures (selon la température de la pièce)\r\n- Dans la dernière demi-heure, on lance le préchauffage du four : préchauffer la cocotte vide et son couvercle au four à 250°C pendant au moins 20 minutes\r\n\r\n## Première cuisson\r\n\r\nLa cuisson se fait en deux sessions : la première avec le couvercle et puissance max, la deuxième sans le couvercle à puissance forte\r\n\r\n- Préchauffer la cocotte vide et son couvercle au four à 250°C pendant au moins 20 minutes\r\n- Mettre la cocotte brûlante sur une surface isolante, ouvrir le couvercle archi-brûlant et le poser non-loin\r\n- Transférer le pâton contenu dans le banneton, dans la cocotte brûlante. Le pâton doit être renversé dans la cocotte, soudures vers le bas. Personnellement j\'utilise le torchon contenant le banneton.\r\n- Scarifier brièvement le dessus du pâton\r\n- Refermer la cocotte et faire cuire pendant 25 minutes à 250°C (selon le four)\r\n\r\nSi vous n\'avez qu\'un pain à faire cuire, passer directement à la deuxième cuisson. Mais **si vous avez une série de pains à faire cuire**, sortez le pain de la cocotte, laissez-le reposer sur une grille, puis répétez la première cuisson à chacun des autres pains.\r\n\r\n## Deuxième cuisson\r\n\r\nLa deuxième cuisson est d\'avantages dédiée à la croûte\r\n\r\n- Baisser la température du four à 200°C\r\n- Retirer le couvercle brûlant de la cocotte et le poser à l\'écart\r\n- Faire cuire pendant 20 minutes à 200°C (selon le four)\r\n\r\n## Voili voilou\r\n\r\n- Disposer le pain sur une grille pour le laisser refroidir\r\n- Gratter un peu l\'excédent de farine\r\n\r\nBah voilà, bien joué ',
                ':datejour' => date('Y-m-d-H-i-s')
            ));
            $lastInsertedRecetteId = $connexion->lastInsertId();

            //Créer une requête qui lie le hashtag "levain" à la recette du "pain au levain".
            $requete = "INSERT INTO `assoc_hashtags_recettes`(`assoc_hr_hashtag_id`, `assoc_hr_recette_id`)
                        VALUES(:hashtagId, :recetteId)";
            $prepare = $connexion -> prepare($requete);
            $prepare->execute(array(
                ':hashtagId' => $lastInsertedHashtagId,
                ':recetteId' => $lastInsertedRecetteId
            ));
            
            //selectionner les recettes ayant le mot clé 'nourriture'
            $requete = "SELECT * FROM recettes 
                        WHERE recette_id IN (SELECT assoc_hr_recette_id FROM assoc_hashtags_recettes 
                        WHERE assoc_hr_hashtag_id IN (SELECT hashtag_id FROM hashtags WHERE hashtag_nom = :hashtag))";
            $prepare = $connexion->prepare($requete);
            $prepare->execute(array(
                ':hashtag' => 'nourriture'
            ));
            echo("<h3>Liste des recettes ayant le mot clé 'nourriture':<h3>");
            while ($donnee = $prepare->fetch()){
                echo("<li>".$donnee['recette_titre']."</li>");
            }

        } catch (PDOException $e) {
            exit ($e->getMessage());
        }
    ?>
</body>
</html>
