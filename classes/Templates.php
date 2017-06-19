<?php
/**
 * класс для работы с шаблона
 */
class Templates {
    /**
     * вывод резальтата объединения шаблона и данных
     *
     * @param  string $filename путь до файла с шаблоном
     * @param  array $data массив с данным для заполнения шаблона
     * @return string готовая страница
     */
    public static function render($filename, $data = [])
    {
        if (file_exists($filename)) {
            extract($data);                 //импортирует переменные из массива
            ob_start();                     //ключается буферизация
            include ($filename);            //подключается шаблон
            $content = ob_get_contents();   //в переменную заносится все из буфера
            ob_end_clean();                 //буфер очищается и тключается
            return $content;
        } else {
            return ("");
        }
    }
}
