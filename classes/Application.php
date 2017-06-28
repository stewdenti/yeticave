<?php
/**
 * Базовый класс для запуска выполнения приложения
 *
 */
class Application
{
    /**
     * запуск приложения на выполнение
     *
     *
     */
    public static function run()
    {
        session_start();

        self::findWinner();

        Router::execute();
    }

    /**
     * поиск всех победителей и отправка писем
     *
     */
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

                self::sendMailTo($lot->winner, $lot->user_id, $lot->id);

            }
        }
    }

    /**
     * отправка писем победителю и владельцу
     * @param  int $winner_id id победителя
     * @param  int $owner_id  id владельца
     * @param  int $lot_id    id лота
     *
     */
    private static function sendMailTo($winner_id, $owner_id, $lot_id) {
        $winner = UserFinder::getById($winner_id);
        $owner = UserFinder::getById($owner_id);
        $lot = LotFinder::getById($lot_id);

        $winner_subject = "Вы стали победителем!!!";
        $owner_subjer = "Для вашего лота нашелся победитель!!!";

        $winner_message = "<p>Уважаемый ".$winner->name."</p> </br>
        <p>Вы стали победителем по результатам аукциона для лота <b>
        <a href=\"http://".$_SERVER["SERVER_NAME"]."/lot/show/id/".$lot->id."\">".$lot->name."</a></b> на сайте <a href=\"http://".$_SERVER["SERVER_NAME"]."\">YetiCave</a></p>
        <p>Свяжитесь с владельцем лота по следующим контактам:<div>".$owner->contacts."</div></p>";

        $owner_message = "<p>Уважаемый ".$owner->name."</p> </br>
        <p>Определился победитель по результатам аукциона для лота <b>
        <a href=\"http://".$_SERVER["SERVER_NAME"]."/lot/show/id/".$lot->id."\">".$lot->name."</a></b> на сайте <a href=\"http://".$_SERVER["SERVER_NAME"]."\">YetiCave</a></p>
        <p>Свяжитесь с победилем по следующим контактам:<div>".$winner->contacts."</div></p>";

        $headers  = "Content-type: text/html; charset=windows-1251 \r\n";

        mail($winner->email, $winner_subject, $winner_message, $headers);
        mail($owner->email, $owner_subject, $owner_message, $headers);
   }

}


?>
