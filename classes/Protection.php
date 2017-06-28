<?php
/**
 * функции защиты от различного вида угроз
 */
class Protection
{
    /**
     * защита от XSS атак в передваемых данных
     *
     * @param  string|array $data строка или массив строк для обработки
     * @return mixed    обработанные данные
     */
    public static function fromXSS($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = protectXSS($value);
            }
            return $data;
        } else if (is_string($data)) {
            return htmlspecialchars(trim($data));
        }
        return $data;
    }
}
