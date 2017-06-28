<?php
/**
 * Класс контроллера для работы со списком лотов, выбора по категории и поиску по ключевым словам
 */
class MainController extends BaseController
{
    /**
     * номер страницы по умолчанию
     * @var integer
     */
    protected $page = 1;

    /**
     * отобразить все лоты в зависимости от переданных параметров по странично и/или по категории
     */
    public function show()
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;
        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        if (!$this->params) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];
            }

            if (!empty($this->params["category"])) {

                $pagination = Paginator::buildPages($this->page, "/main/show", $this->params["category"]);
                $lots_list = LotFinder::getByCategoryId($this->params["category"], $pagination->getOffset());
                $current_category = CategoryFinder::getById($this->params["category"]);

                $this->body_data["category_id"] = $current_category->id;
                $this->body_data["category_name"] = $current_category->name;
                $this->body_data["announcement_list"] = $lots_list;

                if ($pagination->total > 1) {
                    $this->body_data["pages"] = $pagination;
                }
                $this->display("templates/main.php");

            } else {
                $this->default();
            }
        }
    }

    /**
     * найти и вывести все лоты соответствующие ключевым словам по странично.
     */
    public function find()
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;
        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        if (!isset($_GET['search'])) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];

            }
            if (!empty($_GET["search"])) {
                $search = protectXSS(trim($_GET["search"]));
                $pagination = Paginator::buildPages($this->page, "/main/find", null, $search);
                $lots_list = LotFinder::searchByString($search, $pagination->getOffset());
                $this->body_data["search_string"] = $search;
                $this->body_data["announcement_list"] = $lots_list;
                if ($pagination->total > 1) {
                    $this->body_data["pages"] = $pagination;
                }

                $this->display("templates/main.php");
            } else {
                $this->default();
            }
        }
    }

    /**
     * действие по умолчанию, вывод главной страницы
     */
    public function default()
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;
        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        if (!empty($this->params["page"])) {
           $this->page = (int)$this->params["page"];
        }

        $pagination = Paginator::buildPages($this->page, "/main/show");
        $lots_list = LotFinder::getAllOpened($pagination->getOffset());

        if ($pagination->total > 1) {
             $this->body_data["pages"] = $pagination;
        }

        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->body_data["announcement_list"] = $lots_list;


        $this->display("templates/main.php");
    }
}
