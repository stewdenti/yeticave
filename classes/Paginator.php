<?php


class Paginator
{
    public $total = null;
    public $current = null;
    public $offset = null;
    public $back = null;
    public $forward = null;

    /**
     * Создание объекта для построения пагинации
     *
     * Paginator constructor.
     * @param int $total общее количество страниц
     * @param int $current текущая старница
     * @param int $offset оффсет для запроса к базе данных
     */
    public function __construct($total, $current, $offset)
    {
        $this->total = $total;
        $this->current = $current;
        $this->offset = $offset;
        if ($current > 1) {
            $this->back = $current - 1;
        } else {
            $this->back = 1;
        }
        if ($current < $total) {
            $this->forward = $current + 1;
        } else {
            $this->forward = $total;
        }

    }

    /**
     * подготовка url для шаблона.
     *
     * @param string $url имя страницы
     * @param int $num номер страницы
     * @param string|null $search строка поиска
     * @param int|null $categoryId id категории
     *
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
     * @param int|null $page номер текущей страницы
     * @param ing|null $categoryId id катекории
     * @param string|null $search строка запроса
     * @return Paginator
     */
    public static function buildPages ($page = null, $categoryId = null, $search = null)
    {
        if ($categoryId !== null) {
            $total = LotFinder::getCountLots($categoryId);
        } elseif ($search !== null) {
            $total = LotFinder::getCountLotsForSearch($search);
        } else {
            $total = LotFinder::getCountLots();
        }
        if (!$page) {
            $page=1;
        }
        $cnt_pages = ceil($total / BaseFinder::ITEMS_PER_PAGE);
        if ($page > $cnt_pages) {
            $page = $cnt_pages;
        }
        $offset = ($page - 1) * BaseFinder::ITEMS_PER_PAGE;
        
        return new Paginator($cnt_pages, $page, $offset);
    }
}