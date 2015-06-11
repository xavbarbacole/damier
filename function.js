//////////// debut de la partie obligatoire /////////////

// variable contenant la representation de mon tableau en memoire
var montableau;

var tableaupoints;
//crée par xcp pour améliorer la  gestion de l'emplacement des pions
var tabPionsPos;
// tabPionsPos = new Array(nb);


// Nombre de valeurs souhaitees pour mon damier
var nbvaleur;
// variable contenant l'identifiant de mon interval
var id_inter;

// déclare la variable globale pour le nombre de résultat
var nb_res = 0;

// declarer la vitesse de rafraichissement en ms
var delayms = 1;

// A ecrire a partir: Fonction permettant de recuperer l'objet attache a un nom passe en param�tre
// necessaire a partir de : exemple 1
function getElem(nom) {
    var Objdest=(document.getElementById) ? document.getElementById(nom): eval("document.all[nom]");
    return Objdest;
}

function getJqueryElem(nom) {
    var Objdest=$("#"+nom);
    return Objdest;
}
//////////// fin de la partie obligatoire /////////////

function exemple1() {
    alert("Bonjour, je suis la fonction exemple1");
}

function exemple2() {
    var moncontenu=getJqueryElem('contenu');
    var monnbvaleur=getJqueryElem('nbvaleur');
    alert("Voici le nombre de case souhaite :"+monnbvaleur.val());
    alert("Voici le contenu de ma zone dynamique :\n"+moncontenu.html());
}
function exemple3()
{
    var max=10;
    var x;
    var i;

    for (i=1;i<3;i++) {
        x=Math.round(Math.random()*(max-1))+1;
        alert("Voici un nombre aleatoire compris entre 10 et "+max+" : "+x);
    }
}

function exemple4() {
    var moncontenu=getJqueryElem('contenu');
    var max=getJqueryElem('nbvaleur').val();

    x=Math.round(Math.random()*(max-1));
    moncontenu.html("Voici un nombre aleatoire compris entre 0 et "+max+" : "+x);
}

function exemple5() {
    ecriresimpletableau('contenu','nbvaleur');
}

function exemple6() {
    ecriretableau('contenu','nbvaleur');
    generepion('nbvaleur');
}

function exemple7() {
    initialisation('nbvaleur');
    ecriretableau('contenu','nbvaleur');
//    generepion('nbvaleur');
    genereplusieurspions('nbvaleur');
    RendreDessin('nbvaleur');
}

function exemple8() {
    // A vous de jouer
    // Il faut appeler la fonction 
    demarrerInterval();
//    id_inter=window.setInterval(exemple7,delayms)
//    id_inter=window.setInterval(exemple8,delayms)
//    delayms = delayms - 50;
//    $("#nbcall").val(delayms);

}

//xcp : gestion position pions
function exemple9() {
    initialisation('nbvaleur');
    ecriretableau('contenu','nbvaleur');
    genereplusieurspions('nbvaleur');
    RendreDessin('nbvaleur');
    demarrerInterval();

}
//xcp : gestion position pions : afiicher-effacer
function exemple10() {
    nettoyerPion('nbvaleur')
    RendreDessin('nbvaleur');

}


// fonction permettant de creer le damier de couleurs
// necessaire a partir de : exemple 5
function ecriresimpletableau(nom,valeur) {
    var resultat="";
    var li=0;
    var co=0;
    var moncontenu=getJqueryElem(nom);
    var monnbvaleur=getJqueryElem(valeur);
    var nb=monnbvaleur.val();

    resultat="<table border=\"1\" cellspacing=\"0\" width=\"100%\" height=\"100%\">";

    for (li=1;li<=nb;li++) {
        resultat+="<tr>";
        for(co=1;co<=nb;co++) {
            resultat+="<td><div id="+li+"_"+co+">"+(nb*(li-1)+co)+"</div></td>";
        }
        resultat+="</tr>";
    }
    resultat+="</table>";
    moncontenu.html(resultat);
}

// fonction permettant de creer le damier de couleurs
// necessaire a partir de : exemple 6
function ecriretableau(nom,valeur) {
    var resultat="";
    var li=0;
    var co=0;
    var moncontenu=getJqueryElem(nom);
    var nb=getJqueryElem(valeur).val();
    var couleur="#000000";

    resultat="<table border=\"1\" cellspacing=\"0\" width=\"100%\" height=\"100%\">";

    for (li=1;li<=nb;li++) {
        if (li%2>0) couleur="#000000";
        else couleur="#ffffff";

        resultat+="<tr>";
        for(co=1;co<=nb;co++) {
            if (couleur=="#ffffff") couleur="#000000";
            else couleur="#ffffff";

            resultat+="<td width="+(100/nb)+"% height="+(100/nb)+"% bgcolor="+couleur+"><div id="+li+"_"+co+"></div></td>";
        }
        resultat+="</tr>";
    }
    resultat+="</table>";
    moncontenu.html(resultat);
}

// Fonction permettant d'initialiser ma variable montableau en construisant les deux dimensions X et Y
// necessaire a partir de : exemple 7
function initialisation(nom_valeur) {
    var li=0;
    var co=0;
    var nb=getJqueryElem(nom_valeur).val();
    nbvaleur=nb;
    montableau = new Array(nb);
    tableaupoints = new Array(nb);

    //xcp : gestion position des pions    
    tabPionsPos = new Array(nb);
    // fin : gestion position des pions

    for (li=0;li<nb;li++) {
        montableau[li]=new Array(nb);

        tableaupoints[li]= new Array(2);
        tableaupoints[li][0]=0;
        tableaupoints[li][1]=0;

        for(co=0;co<nb;co++) {
            montableau[li][co]="0";
        }
    }
}

// Fonction permettant de modifier et afficher chaque cellule du tableau
// en fonction des valeurs contenues dans ma variable montableau
function RendreDessin(nom_valeur) {
    var macase;

    var nb=getJqueryElem(nom_valeur).val();

    for (li=0;li<nb;li++) {
        for(co=0;co<nb;co++) {
            macase=getJqueryElem((li+1)+"_"+(co+1));

            if (montableau[li][co]=="1") {
                macase.html("<img src='./pion.gif'>");
            }
            else {
                macase.html("&nbsp;");
            }
        }
    }
}

/*
Fonction permettant de ne dessiner que ce qui a été généré
 */
function RendreDessinEvolue(nom_valeur) {
    var macase;
    var x,y=0;
    var nb=getJqueryElem(nom_valeur).val();

    for (li=0;li<nb;li++) {
        x=tableaupoints[li][0];
        y=tableaupoints[li][1];

        // A vous de jouer
        // affichage du pion
    }
}

// Fonction permettant de modifier le contenu d'une cellule de tableau de facon aleatoire
function generepion(nom_valeur) {
    var nbvaleur=getJqueryElem(nom_valeur).val();
    var x=0;
    var y=0;

    x=Math.round(Math.random()*(nbvaleur-1))+1;
    y=Math.round(Math.random()*(nbvaleur-1))+1;
    macase=getJqueryElem(x+"_"+y);
    macase.html("<img src='./pion.gif'>");
}

// Fonction permettant de calculer de facon aleatoire 'nbvaleur' cellules vides
// necessaire a partir de : exemple 7
function genereplusieurspions(nom_valeur) {

    var nbvaleur=getJqueryElem(nom_valeur).val();
    var x=0;
    var y=0;
    var present="1";
    nb_res = nb_res +1 ;

    // xcp : rajout de la gestion position des pions
    var pos = new Array;
    // fin : rajout de la gestion position des pions
    //getJqueryElem('nbresultat').val(nb_res);
    $('#nbresultat').val(nb_res);
    for (i=0;i<nbvaleur;i++) {
        present="1";
        while (present=="1") {
            x=Math.round(Math.random()*(nbvaleur-1));
            y=Math.round(Math.random()*(nbvaleur-1));
            present=montableau[x][y];
            // xcp : rajout de la gestion position des pions
            pos['x']=x;pos['y']=y;
            tabPionsPos[i]=pos;
            // fin : gestion position des pions
        }
        montableau[x][y]="1";

        // a vous de jouer pour stocker les coordonnes generees dans la structure tableaupoints

    }
}

// fonction permettant de lancer toutes les X millisecondes la fonction passee en parametre 'genererAll'
// necessaire a partir de : exemple 8
function demarrerInterval() {
    id_inter=window.setInterval(exemple10,1000);
}


function nettoyerPion(nom_valeur) {
    // Faire le code de nettoyage sur base des elements contenus ds le tableau tableaupoints
    var x,y=0;
    var nb=getJqueryElem(nom_valeur).val();
    //xcp : gestion position pions
    var pos = new Array;
    // fin : gestion position pions

    for (li=0;li<nb;li++) {
        //xcp : gestion position pions
        /*
        x=tableaupoints[li][0];
        y=tableaupoints[li][1];
        */
        pos = tabPionsPos[li];
        montableau[pos['x']][pos['y']]="";
        macase.html("&nbsp;");
        // fin : gestion pions

        // A vous de jouer
        // nettoyage du pion

    }
}
// fonction permettant de calculer l'ensemble du damier
function genererAllEvolue() {
    // Partie de code appelé à chaque cycle de timer
    nettoyerPion('nbvaleur');
    genereplusieurspions('nbvaleur');
    RendreDessinEvolue('nbvaleur');
}

function demarrerIntervalEvolue() {
    /* a vous de jouer */
    // on doit initialiser une seule fois la matrice et le damier
    // Pour chaque appel :
    // Nettoyer le damier si deja genere
    // generer les nouveaux pions et stocker les valeurs dans le tableau tableaupoints
    // afficher les nouveaux pions sur base du tableau tableaupoints
    initialisation('nbvaleur');
    ecriretableau('contenu','nbvaleur');
    id_inter=window.setInterval(genererAllEvolue,1000);
}

// fonction permettant d'arreter l'interval
// necessaire a partir de : exemple 8
function arretInterval() {
    window.clearInterval(id_inter);
    delayms = delayms - 100 ;
    $('#nbcall').val(delayms);
}

function CallSampleBuildAjax() {
    $.ajax({
        type: "POST",
        url: "index.php",
        data: {
            'op' : 'buildSampleJson',
            'nbvalue' : getJqueryElem('nbvaleur').val()
        },
        dataType: "json",
        success: function(data){
            if (data.nbcall) {
                getJqueryElem('nbcall').val(data.nbcall);
            }

            if(data.tabpoints.length != 0) {
                var resultat="";

                for(var indice in data.tabpoints) {
                    resultat+=("Nouvel ensemble de pionts : "+data.tabpoints[indice].x+" "+data.tabpoints[indice].y+"<br>");
                }
                var moncontenu=getJqueryElem("contenu");
                moncontenu.html(resultat);
            }


        },
        error: function(data){}
    });
}