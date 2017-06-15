<?php
/**
 * Created by PhpStorm.
 * User: boss
 * Date: 09.01.2017
 * Time: 21:20
 */

namespace core;


class Renderer
{
    public static function put($controller, $view, $data)
    {
        $view_path = self::viewPath($controller,$view);

        $layout_path = self::layoutPath($controller->layout);

        $view_content = self::getViewContent($view_path,$data);

       include $layout_path;
    }

    private static function getViewContent($view,$data)
    {
        extract($data);
        ob_start();
        include ($view);
        $content = ob_get_contents();
        ob_end_clean();
        return $content;
    }

    private static function layoutPath($layout)
    {

        return ROOT_DIR."/"."components/layouts/".$layout.".php";
    }

    private  static function viewPath($controller,$view)
    {
        return ROOT_DIR."/"."components/views/".$controller->shortControllerName()."/".$view.".php";
    }

}