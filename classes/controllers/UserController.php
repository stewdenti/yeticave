<?php
/*
Класс котроллера для работы с пользователями: регистрация,авторизация
 */
class UserController extends BaseController
{
    /**
     * Авторизация существующих пользователей
     */
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

        if (isset($this->params["welcome"])) {
            $this->body_data["w"] = true;
        }

        $this->display("templates/main-login.php");
    }

    /**
     * Регистрация новых пользователей
     */
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

    /**
     * выход из сессии пользователя
     */
    public function signout()
    {
        Authorization::blockAccess();
        Authorization::logout();
        header("Location: /main");
    }

    /**
     * Отображение всех лотов для которых пользователь сделал ставку
     */
    public function mylots()
    {
        Authorization::blockAccess();
        $this->body_data["rates"] = BindFinder::getByUserId($this->user->id);
        $this->body_data["userid"] = $this->user->id;
        $this->display("templates/mylot-main.php");
    }
}
