<?php
/**
 * класс для получения настроек для базы данных
 */

class ConfigManager
{
    /**
     * Получение настроек из файла и возвращение их ввиде ассоциативного массива
     *
     * @return array   ассоциативный массив с настройками
     */
    public static function get()
    {
        require_once ("config/config.php");
        return $config;
    }

    public static function getSettings($name = null)
    {
        if ($name) {
            require_once ("config/config.php");
            return $config[$name];
        } else {
            return null;
        }

    }

}


?>
