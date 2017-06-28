<?php
/**
 * Базовый класс котроллеров
 */
class BaseController
{
    /**
     * параметры полученные их URL
     * @var null
     */
    protected $params = null;
    /**
     * данные пользователя хранимые в сессии
     * @var null
     */
    protected $user = null;

    /**
     * данные для шаблона header
     * @var array
     */
    protected $header_data = array();
    /**
     * данные для шаблона body
     * @var array
     */
    protected $body_data = array();
    /**
     * Данные для шаблона footer
     * @var array
     */
    protected $footer_data = array();

    /**
     * Конструктор для контроллера.
     * @param array $params параметры, полученные из URL
     */
    public function __construct($params = null)
    {

        $this->params = $params;
        $this->authenticate();
    }
    /**
     * Получение данных об авторизованном пользователе хранящихся в сессии
     */
    public function authenticate()
    {
        $this->user = Authorization::getAuthData();
    }
    /**
     * сформировать данные и передать в шаблон для отобржаения
     * @param  string $body_template имя шаблона для body
     */
    public function display($body_template)
    {
        $this->header_data["user"] = $this->user;
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();


        echo Templates::render("templates/header.php", $this->header_data);
        echo Templates::render($body_template, $this->body_data);
        echo Templates::render("templates/footer.php", $this->footer_data);
    }
    /**
     * действие для котроллера по умолчанию
     */
    public function default()
    {
        header("Location: /main");
    }

}
