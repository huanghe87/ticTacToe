<!DOCTYPE html>

<html>
<head>
    <meta charset="UTF-8">
    <title>井字棋</title>

    <link rel="stylesheet" href="web/css/style.css">

</head>

<body>

<table>
    <?php
    foreach($board as $pos => $cell){
        if($pos % 3 == 0){
            if($pos > 0){
                echo '</tr>';
            }
            echo '<tr>';
        }
        echo '<td class="cell" id="'.$pos.'">';
        if($cell == PLAYER_A){
            echo CELL_O;
        }else if($cell == PLAYER_B){
            echo CELL_X;
        }
        echo '</td>';
    }
    ?>
</table>
<div class="endgame" <?php if(!empty($gameOver)){echo 'style="display:block"';} ?>>
    <div class="text"><?php if(!empty($gameOver)){echo $gameOver;} ?></div>
</div>
<!--<div class = "selectSym">-->
<!--    <p>Select symbol:</p>-->
<!--    <button onClick="selectSym('X')">X</button>-->
<!--    <button onClick="selectSym('O')">O</button>-->
<!--</div>-->
<button onClick="startGame()">Replay</button>
<script src="web/js/script.js"></script>
</body>
</html>