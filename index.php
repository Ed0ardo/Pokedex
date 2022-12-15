<?php
    $usrID = $_GET['id'] ?? '000000000';
    $pokeJson = fopen("pokedex.json", "r") or die("Unable to open file!");
    $pokemon = fread($pokeJson,filesize("pokedex.json"));
    fclose($pokeJson);
    $pokemon = json_decode($pokemon, true);

    function cmp($a, $b){
        if ($a == $b) {
            return 0;
        }
        return ($a < $b) ? -1 : 1;
    }

    $servername = "0.0.0.0";
    $username = "Ed0ardo";
    $password = "XXX";
    $dbname = "pokedex";

    $conn = new mysqli($servername, $username, $password, $dbname);
    
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokédex Me</title>
    <link rel="apple-touch-icon" type="image/png" sizes="192x192" href="favicon.png">
    <link rel="icon" type="image/png" sizes="192x192" href="favicon.png">
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <script src='kofi.js'></script>
    <script>
        kofiWidgetOverlay.draw('ed0ardo', {
            'type': 'floating-chat',
            'floating-chat.donateButton.text': '',
            'floating-chat.donateButton.background-color': '#323842',
            'floating-chat.donateButton.text-color': '#fff'
        });
    </script>
    <script>
        function openPokemon(num) {
            if(num){
                window.open("https://www.pokemon.com/us/pokedex/" + num, '_blank').focus();
            }
            else{
                window.open("https://t.me/PokedexMeBot", '_blank').focus();
            }
        }
    </script>
    <h1 class="pokeTitle" onclick="openPokemon();">PokédexMe</h1>
    <div class="contenitor">
        <input type="text" id="searchBar" onkeyup="searchPokemon()" placeholder="Search Pokémon...">
        <ul class="results" id="ul">
            <?php
                if ($usrID) {
                    $sql = "SELECT pokemon FROM pokeball WHERE id = ".$usrID;
                    $r = $conn->query($sql);
                    echo '<p id="nPoke">'.$r->num_rows.'/'.count($pokemon).'</p>';
                    if ($r->num_rows > 0) {
                        $row = $r->fetch_all(MYSQLI_NUM);
                        usort($row, "cmp");
                        foreach($row as $num){
                            $li = '<li onclick="openPokemon('.$num[0].');" class="'.strtolower($pokemon[$num[0]]["types"][0]).'">
                                        <div class="cont">
                                            <figure>
                                                <img src="'.$pokemon[$num[0]]["photo"].'">
                                            </figure>
                        
                                            <div class="pokemon-info">
                                                <p class="id">
                                                    <span class="number-prefix">#'.$num[0].'</span>
                                                    <img class="type" src="https://raw.githubusercontent.com/duiker101/pokemon-type-svg-icons/master/icons/'.strtolower($pokemon[$num[0]]["types"][0]).'.svg">
                                                </p>
                                                <h5>'.$pokemon[$num[0]]["name"].'</h5>
                                            </div>
                                        </div>
                                    </li>';
                            echo $li;
                        }
                    }
                }
                echo '</ul>';
            ?>
    </div>
    <script>
        function searchPokemon() {
          var input, filter, ul, li, i, txtValue;
          input = document.getElementById('searchBar');
          filter = input.value.toUpperCase();
          ul = document.getElementById("ul");
          li = ul.getElementsByTagName('li');

          for (i = 0; i < li.length; i++) {
            txtValue = li[i].innerText
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
              li[i].style.display = "";
            } else {
              li[i].style.display = "none";
            }
          }
        }
    </script>
</body>

</html>
