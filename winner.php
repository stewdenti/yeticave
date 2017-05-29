<?php
include ("autoload.php");


$lots_without_winners = LotFinder::getWithoutWInners();

var_dump($lots_without_winners);

foreach ($lots_without_winners as $lot) {
    var_dump($lot);
    $winner_bind =  BindFinder::getByLotId($lot->id);
    var_dump($winner_bind);
    if ($winner_bind && $lot->user_id != $winner_bind[0]->user_id) {
        $lot->winner = $winner_bind->user_id;
        $lot->update();
    }
    var_dump($lot);
}


?>