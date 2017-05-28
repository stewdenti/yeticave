<?php


class Paginator
{
    /**
     * Создание объекта для построения пагинации
     *
     * Paginator constructor.
     * @param $total общее количество страниц
     * @param $current текущая старница
     * @param $offset оффсет для запроса к базе данных
     */
    public function __construct($total, $current, $offset)
    {
        $this->total = $total;
        $this->current = $current;
        $this->offset = $offset;
        $this->back = 1;
        if ($current > 1){
            $this->back = $current-1;
        }
        $this->forward = $total;
        if ($current < $total) {
            $this->forward = $current+1;
        }

    }

    /**
     * подготовка url для шаблона.
     *
     * @param $url имя страницы
     * @param $num номер страницы
     * @param null $search строка поиска
     * @param null $categoryId id категории
     * @return string готовый URL для вывода в шаблоне
     */
    public function prepareURL($url, $num, $search = null, $categoryId = null)
    {
        $url .= "?page=$num";
        if ($search !== null) {
            $url .= "&search=$search";
        }
        if ($categoryId !== null) {
            $url .= "&id=$categoryId";
        }
        return $url;
    }

    /**
     * Вычисление количества страниц, на основе текущей страницы
     * для всех лотов, для лотов по категории, для поиска по строке
     *
     * @param null $page номер текущей страницы
     * @param null $categoryId id катекории
     * @param null $search строка запроса
     * @return Paginator
     */
    public static function buildPages ($page = null, $categoryId = null, $search = null)
    {
        if ($categoryId !== null){
            $total = LotFinder::getCountLots($categoryId);
        } elseif ($search !== null) {
            $total = LotFinder::getCountLotsForSearch($search);
        } else {
            $total = LotFinder::getCountLots();

        }
        if (!$page) $page=1;
        $cnt_pages = ceil( $total / ITEMS_PER_PAGE );

        if ( $page > $cnt_pages ) $page = $cnt_pages;
        // Начальная позиция
        $offset = ( $page - 1 ) * ITEMS_PER_PAGE;
        
        return new Paginator($cnt_pages, $page, $offset);
    }
}