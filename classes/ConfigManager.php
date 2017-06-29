<?php
/**
 * класс для получения настроек для базы данных
 */

class ConfigManager
{
    /**
     * Получение настроек из файла и возвращение их ввиде ассоциативного массива
     *
     * @return array   ассоциативный массив с настройками     * 
     */
    protected static $configInstance = null;

    protected $config = null;

    protected function __construct($data = null)
    {
        $this->config = $data;
    }

    public function __destruct()
    {
        $this->config = null;
    }

    public static function getConfig()
    {
        if (self::$configInstance == null) {
            include_once ($_SERVER["DOCUMENT_ROOT"]."/config/config.php");
            self::$configInstance = new self($config);
        }
        return self::$configInstance;
    }

    public function __get($name = null)
    {
        if ($name && isset($this->config[$name])) {
            return $this->config[$name];
        } else {
            return null;
        }
    }

}


?>
