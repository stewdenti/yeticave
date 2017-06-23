<?php

/**
 * Класс для вычисления, построения постраничного вывода
 *
 * Class Paginator
 */
class Paginator
{
    /**
     * Количество элементов на странице
     */
    const ITEMS_PER_PAGE = 9;
    /**
     * общее количество страниц
     * @var int|null
     */
    public $total = null;
    /**
     * номер текущей страницы
     * @var int|null
     */
    public $current = null;
    /**
     * номер предыщей страницы
     * @var int|null
     */
    public $back = null;
    /**
     * Номер следующей страницы
     * @var int|null
     */
    public $forward = null;

    public $url = null;

    /**
     * Создание объекта для построения пагинации
     *
     * Paginator constructor.
     * @param int $total общее количество страниц
     * @param int $current текущая старница
     */
    public function __construct($url, $total, $current)
    {
        $this->total = $total;
        $this->current = $current;
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
        $this->url = $url;
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
    public function prepareURL($num, $search = null, $categoryId = null)
    {
        $url = $this->url;
        $url .= "/page/$num";
        if ($search !== null) {
            $url .= "?search=$search";
        }
        if ($categoryId !== null) {
            $url .= "/category/$categoryId";
        }
        return $url;
    }

    /**
     * Получение оффсета для запроса
     *
     * @return int|null
     */
    public function getOffset()
    {
        return ($this->current - 1) * Paginator::getItemsPerPage();
    }
    /**
     * Вычисление количества страниц, на основе текущей страницы
     * для всех лотов, для лотов по категории, для поиска по строке
     *
     * @param int|null $page номер текущей страницы
     * @param int|null $categoryId id катекории
     * @param string|null $search строка запроса
     * @return Paginator
     */
    public static function buildPages($page = null, $url = null, $categoryId = null, $search = null)
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
        $cnt_pages = ceil($total / Paginator::getItemsPerPage());
        if ($page > $cnt_pages) {
            $page = $cnt_pages;
        }
        return new Paginator($url, $cnt_pages, $page);
    }

    public static function getItemsPerPage ()
    {
        return self::ITEMS_PER_PAGE;
    }

}
