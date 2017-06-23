<?php

class UserController extends BaseController
{


    public function signin() 
    {
        if (isset($_POST["AuthForm"])) {
            $form = AuthForm::getFormData();
            if ($form->isValid()) {
                $user = Authorization::authorize($form->email, $form->password);
                if ($user === true) {
                    //если пароль верен, отправляем пользователя на главную стр
                    header("Location: /user/mylots");
                    exit();
                } else {
                    $this->body_data["error"] = $user;
                }
            } else {
                $this->body_data["error"]= $form->getErrors();
            }
        } else {
            $this->body_data["error"] = array();
        }

        if (isset($this->param["welcome"])) {
            $this->body_data["w"] = true;
        }

        $this->display("templates/main-login.php");
    }

    public function signup()
    {
        if (isset($_POST["RegForm"])) {
            $form = RegForm::getFormData();

            if ($form->isValid()){
                $user = new User($form->getData());
                $user->insert();
                header("Location: /user/signin/welcome/".$user->name);
                exit();
            } else {
                $this->body_data["error"] = $form->getErrors();
                $this->body_data["form_item"] = $form->getData();
            }

        } else {
            $this->body_data["error"] = array ();
            $this->body_data["form_item"] = array();
        }

        $this->display("templates/registration-main.php");

    }

    public function signout()
    {
        Authorization::blockAccess();

        Authorization::logout();
        header("Location: /main");
    }

    public function mylots()
    {
        Authorization::blockAccess();

        $categories = CategoryFinder::getAll();
        
        $this->body_data["rates"] = BindFinder::getByUserId($this->user->id);

        $this->display("templates/mylot-main.php");

    }

}