<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
    <title>Damier pions al&eacute;atoires dans tableau </title>
    <script language="JavaScript" src="./jquery-1.11.2.min.js"></script>
    <script language="JavaScript" src="./function.js?v=2"></script>
</head>
<body>
<table cellspacing="1" border="1" cellpadding="0" style="border-color:black;border-width: 20px;border-style:solid;bordercolor:#0123ff;width:100%;height:100%">
    <tr style="height:10%">
        <td >
            <input type="button" id="ok" name="ok" value="A toi de jouer" >
<!--             <input type="button" id="ok" name="ok" value="A toi de jouer" onClick="exemple8();">
-->
            <input type="button" name="arret" value="Arreter" onClick="javascript:arretInterval();">
            Nb de pions <input type="text" id="nbvaleur" name="nbvaleur" id="nbvaleur" value="10">
            Nb resultat <input type="text" name="nbresultat" id="nbresultat">
            Nb appel <input type="text" name="nbcall" id="nbcall">
        </td>

    </tr>
    <tr style="height:90%">
        <td >
            <div id="contenu" style="height:100%;">Ici je vais afficher mon damier</div>
        </td>
    </tr>

</table>
<script>
$(document).ready(function(){
    $("#ok").click(function{
        $.post(
            'actu.php', // Un script PHP que l'on va créer juste après
            { 
                op : 'displaytableau';  // Nous récupérons la valeur de nos inputs que l'on fait passer à connexion.php
                nbvaleur : $("#nbvaleur").val();
            },
            function(data){ // Cette fonction traite le message renvoyé par le serveur
            },
            'html' // Nous recevons le code sql qui affiche un tableau !
         );
    });
});
</script>

</body>
</html>
