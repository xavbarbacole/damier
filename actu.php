
<?php
/*require_once('config.php');
require_once(APP_PATH.'/lib/db.php');
*/

function bdd()
{
    try
    {   // PDO et la traque des erreurs dans le code sql avec array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        $bdd = new PDO('mysql:host=localhost;dbname=damier_hist;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }
    return $bdd;
}

/*****************************************
fichier de test permettant l'execution de commande en ligne
l'url doit être de la fomr
index.php?op=displaytexte&contenu=ma_chaine_de_carac

valeur possible des arguments
op
displaytexte                contenu=ma_chaine_de_carac
displaytableau              nbvaleur=5
buildSampleJson
/*****************************************/
if (isset($_GET["op"])) $op=$_GET["op"];
if (isset($_POST["op"])) $op=$_POST["op"];

if (isset($op)) {
    switch ($op) {
        // résultat de l'op displaytexte
        case 'displaytexte': {
            // suppression d'un contenu précédent
            while (@ob_end_clean()) ;
            header('Content-type: text/html; charset=UTF-8');
            ob_start();

            // récupération des variables en get et post
            if (isset($_GET["contenu"])) $contenu = $_GET["contenu"];
            if (isset($_POST["contenu"])) $contenu = $_POST["contenu"];

            echo $contenu;

            // arret de l'envoi
            @ob_end_flush();
            die();
        }
            break;

        // résultat de l'op displaytableau
        case 'plateau':
            // requête préparée par remplacement séquencé des paramètres
            //$id_plateau = 1 ;
            if (isset($_GET["id_plateau"])) $id_plateau = $_GET["id_plateau"];
            if (isset($_POST["id_plateau"])) $id_plateau = $_POST["id_plateau"];
            $bdd=bdd();
            $req = $bdd->prepare('SELECT nb_pions, num_cycle FROM PLATEAU WHERE id_plateau = :id_plateau ');
            $req->bindParam(':id_plateau',$id_plateau, PDO::PARAM_INT);
            $req->execute(); 
            while ($data = $req->fetch()){
                $nbvaleur=$data['nb_pions'];
                $nbresultat=$data['num_cycle'];
            }
            $req->closeCursor(); 
            unset($req);
            unset($req);
            $resultat = "";
            if (isset($nbvaleur)) {
            $resultat .= "<table border=1 cellspacing=0 width=100% height=100%>";
            for ($li = 1; $li <= $nbvaleur; $li++) {
                if ($li % 2 > 0) $couleur = "#000000";
                else $couleur = "#ffffff";

                $resultat .= "<tr>";
                for ($co = 1; $co <= $nbvaleur; $co++) {
                    if ($couleur == "#ffffff") $couleur = "#000000";
                    else $couleur = "#ffffff";
                    $resultat .= "<td id=" . $li . "_" . $co . " width=" . (100 / $nbvaleur) . "% height=" . (100 / $nbvaleur) . "% bgcolor=" . $couleur . "><div width='100%' height='100%' >&nbsp;</div></td>";
                }
                $resultat .= "</tr>";
            }
            $resultat .= "</table>";
            } else {
                $resultat = "<p> le damier que vous avez désigné n'est pas disponnible</p>";
                $nbvaleur = 0;
                $nbresultat = 0;
            }
            $json = array(
                'nbresultat' => $nbresultat,
                'nbvaleur' => $nbvaleur,
                'html' => $resultat
                );
            echo json_encode($json);
            // arret de l'envoi
//    $bdd->close;

            die();
            break;
        case 'displaytableau': {
            // suppression d'un contenu precedent
            while (@ob_end_clean()) ;
            header('Content-type: text/html; charset=UTF-8');
            ob_start();

            // récupération des variables en get et post
            if (isset($_GET["nbvaleur"])) $nbvaleur = $_GET["nbvaleur"];
            if (isset($_POST["nbvaleur"])) $nbvaleur = $_POST["nbvaleur"];
            if (isset($_GET["id_plateau"])) $id_plateau = $_GET["id_plateau"];
            if (isset($_POST["id_plateau"])) $id_plateau = $_POST["id_plateau"];
            if (isset($_GET["nbresultat"])) $nbresultat = $_GET["nbresultat"];
            if (isset($_POST["nbresultat"])) $nbresultat = $_POST["nbresultat"];
            //$nbresultat = $nbresultat + 1;
    try
    {   // PDO et la traque des erreurs dans le code sql avec array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        $bdd = new PDO('mysql:host=localhost;dbname=damier_hist;charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e)
    {
            die('Erreur : ' . $e->getMessage());
    }
            $id_joueur=1;
            $id_session=1;
            // les x cases modifier
            $alldata = array();
            $cond = array();
            $val = array();
            if ($nbvaleur> 0) { // il faut être dans un cycle
	            $val = array();
	            for ($i=0; $i<$nbvaleur; $i++) {
	                $alldata[$i] = array('x'=>rand(0,$nbvaleur), // 0 inclus nbval exclus
	                                    'y'=>rand(0,$nbvaleur),
	                                    'num_cycle'=>$nbresultat,
	                                    'id_joueur'=>$id_joueur,
	                                    'id_plateau'=>$id_plateau,
	                                    'id_session'=>$id_session);
	//                $cond[] = '('.implode(",",$alldata[$i]).')'; // pdo avec les nom des champs
	                $val = array_merge($val,array_values($alldata[$i])); // PDO avec les ? : fusionne toutes les val en un tableau
	                $cond[] ='('. str_repeat('?,', count($alldata[$i])-1).'?)'; // PDO avec les ?
	            }

	            $req_sql ="INSERT INTO HISTO ";
	            $req_sql .= "(".implode(",",array_keys($alldata[0])).")";
	            $req_sql .= " VALUES ";
//	            $req_sql .= "(:".implode(", :",array_keys($alldata[0])).")"; // PDO avec nom des champs
	            $req_sql .= implode(" , ", $cond);

	            $req = $bdd->prepare($req_sql);
//	            $bdd->executemultiple($req,$alldata); // $req->execute(array(':id_plateau'=>$id_plateau));
	            $req->execute($val);
	            $req->closeCursor(); 
            unset($alldata);
            unset($cond);
            unset($val);
            unset($req);
            $req_sql = '';

	            // met à j les autres cases non solicités en retranchant - 1
            	// NOTE la fonction mod ne fonctionne pas comme sur excel, elle retourne une valeur négative
            	// 'mod(0 - 1, 60) = -1 alors que  (1-sign(0))*60 +(0-1) = 59
	            $req = $bdd->prepare('UPDATE MATRICE SET color = ((1-sign(color))*60 +(color-1)) WHERE id_plateau = :id_plateau AND num_cycle <> :num_cycle');
	            $req->execute(array(':id_plateau'=>$id_plateau,':num_cycle'=>$nbresultat));
	            $req->closeCursor(); 
            unset($req);

	            //  charge les valeurs des couleurs
	            $req = $bdd->prepare('SELECT r,g,b, x, y from matrice as m Inner join couleur as c on c.id = m.color  WHERE id_plateau = :id_plateau ');
	            $req->bindParam(':id_plateau',$id_plateau, PDO::PARAM_INT);
	            $req->execute(); // $req->execute(array(':id_plateau'=>$id_plateau));

	            $matrice = array();
	        	while ($data = $req->fetch()){
	        		$matrice[$data['x'].'_'.$data['y']]="#".substr("0".dechex($data['r']),-2).substr("0".dechex($data['g']),-2).substr("0".dechex($data['b']),-2);
	        	}
	        	$req->closeCursor(); 
	        unset($req);
	        unset($data);
//		    $bdd->close;
	            echo json_encode($matrice);
            } else
            {  // le num de cycle vaut 0
            	http_response_code(400); //400	Mauvaise requête 	La requête HTTP n'a pas pu être comprise par le serveur en raison d'une syntaxe erronée.
            }


            die();
        }
            break;

        case 'buildSampleJson':
            ob_end_clean();

            $nbvalue = intval($_POST['nbvalue']);

            $result = array('nbcall' => 1, 'tabpoints' => array());

            for ($i = 1; $i <= $nbvalue; $i++) {
                $x=rand(1,$nbvalue);
                $y=rand(1,$nbvalue);
                $point = array('x' => $x, 'y' => $y);
                $result['tabpoints'][]=$point;
            }

            echo json_encode($result);
            die();
            break;
        default:
            echo "Bonjour vous ds la partie default";
            break;
    }
}
?>