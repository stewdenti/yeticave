<?php

class LotController extends BaseController
{
    public function show()    
    {
        if (!empty($this->params["id"])) {
            $this->getInfo();
            $this->display("templates/main-lot.php");
        }
    }

    public function bind()
    {
        
        $this->getInfo();

        $time  = time();
        $form = AddBindForm::getFormData();

        if ($form->isValid()) {
            $lot_item = LotFinder::getById($form->lot_id);
            $maxBet = $lot_item->getMinNextBet();
            if ((int)$form->price < $maxBet) {
                $error['price'] = "Ставка должна быть больше ".$maxBet;
            } else {
                $data = array (
                    "user_id" => $this->user->id,
                    "lot_id" => (int)$form->lot_id,
                    "price" => (int)$form->price,
                    "date" => date("Y:m:d H:i:s")
                );

                $b = new Bind($data);
                $b->insert();
                header("Location: /lot/show/id/".$b->lot_id);
                exit();
            }
        } else {
            $error = $form->getErrors();
        }

        if ($error) {
            $this->body_data['error'] = $error;
            // echo Templates::render("templates/header.php", $header_data);
            // echo Templates::render("templates/main-lot.php", $data);
            // echo Templates::render("templates/footer.php", $data_footer);
            $this->display("templates/main-lot.php");
        }
    }

    public function default()
    {
        header("Location: /main");
    }


    protected function getInfo()
    {
                    // $user_data = Authorization::getAuthData();

            $categories = CategoryFinder::getAll();
            //заполняем данные для шаблона header
            // $header_data["user"] = $user_data;
            $this->header_data["categories_equipment"] = $categories;
            //заполняем данные для шаблона main
            $this->body_data["user"] = $this->user;
            $this->body_data["categories_equipment"] = $categories;
            
            //заполняем данные для шаблона footer
            $data_footer["categories_equipment"] = $categories;


            // проверка пришел ли id лота и получение данных о лоте  из базы
            $lot_item = null;
            $lot_bets = [];
            $bets = null;
            $lot_id = $this->params["id"];

            $this->body_data["can_make_bet"] = false;

            if ($this->user) {
                $this->body_data["can_make_bet"] = BindFinder::canMakeBet($lot_id, $user_data->id);

            }

            if (!empty($lot_id) && is_numeric($lot_id)) {
                $lot_item = LotFinder::getById($lot_id);
                $lot_bets = BindFinder::getByLotID($lot_id);
            }

                //подготовка данных их базы для шаблона.
            $this->body_data["lot_item"] = $lot_item;
                //Получение данных о ставках для лота из базы
            $this->body_data["bets"] = $lot_bets;
    }

}