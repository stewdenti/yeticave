<?php

class Templates {

  public static function render($filename, $data)
  {
      if (file_exists($filename)) {
          foreach ($data as $key => $value){
             $data[$key] = protectXSS($value);
          }
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
