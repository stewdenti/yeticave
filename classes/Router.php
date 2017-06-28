<?php
/**
 * Класс для обработки ЧПУ и выбора соответствующего контроллера
 *
 */
class Router
{
    /**
     * Метод для разбора запрошенного URL и сохранения его в параметры
     *
     * @return array массив данных контролер, действие, параметры
     */
    protected static function parse()
    {
        $application_data = [];
        $params = null;
        $url = explode("?", $_SERVER["REQUEST_URI"], 2);
        $data = explode("/", substr($url[0], 1), 3);

        if (count($data) == 2) {
            $data = Protection::fromXSS($data);
            $controller = $data[0]."Controller";
            $action = $data[1];

        } elseif (count($data) == 3) {
            $controller = Protection::fromXSS($data[0])."Controller";
            $action = Protection::fromXSS($data[1]);
            $params = self::parseQuery($data[2]);
        } else {
            $controller = Protection::fromXSS($data[0])."Controller";
            $action = "default";
        }

        return array (
            "controller" => ucfirst($controller),
            "action" => $action,
            "params" => $params
                      );
    }
    /**
     * Разбор URL и сохранения параметров
     * @param  string $qs строка с параметрами вида param/value
     * @return array     ассоциативный массив param=>value
     */
    protected static function parseQuery($qs)
    {
        $params = [];
        $data = explode("/", $qs);

        for ($i = 0; $i < count($data); $i+=2) {
            if (isset($data[$i]) && isset($data[$i+1])) {
                $params[Protection::fromXSS($data[$i])] = Protection::fromXSS($data[$i+1]);
            }
        }
        return $params;
    }

    /**
     * проверка и запуск выполнения контролера
     *
     */
    public static function execute()
    {
        $ex = self::parse();

        if (class_exists($ex["controller"]) && method_exists($ex["controller"], $ex["action"])) {
            $control = new $ex["controller"]($ex["params"]);
            $method = $ex["action"];
            $control->$method();
        } else {
            $control = new MainController();
            $control->default();
        }
    }


}


?>
