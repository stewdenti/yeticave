<?php

class Application 
{

    public static function run()
    {
        session_start();
        
        self::findWinner();
        
        Router::execute();


    }

    private static function findWinner ()
    {

        $lots_without_winners = LotFinder::getWithoutWinners();

        // для каждого найденого лота :
        foreach ($lots_without_winners as $lot) {
            //получим список всех ставок. Первая в списке будет принадлежать победителю.
            $winner_bind =  BindFinder::getByLotId($lot->id);
            // если ставки были сделаны и первая в массиве = последняя по времени = Максимальная ставка
            // и пользователь сделавший ставку не является создателем лота
            if ($winner_bind && $lot->user_id != $winner_bind[0]->user_id) {
                //проставляем поле winner id пользователя сделавшего максимальную ставку
                //и обновляем запись
                $lot->winner = $winner_bind[0]->user_id;
                $lot->update();
            }
        }
    }        

}


?>