<?php

class Main extends BaseController
{
    protected $page = 1;
        
    public function show() 
    {
        if (!$this->params) {
            $this->default();
        } 

        if (!empty($this->params["page"])) {
            $this->page = (int)$this->params["page"];
        } 


        if (!empty($this->params["category"])) {
            $page = Paginator::buildPages($this->page, $this->params["category"]);
            $lots_list = LotFinder::getByCategoryId(protectXSS($this->params["category"], $page->getOffset());
            $current_category = CategoryFinder::getById($this->params["category"]);
            $data["category_id"] = $current_category->id;
            $data["category_name"] = $current_category->name;
        } elseif (!empty($this->params["search"])) {
            $search = protectXSS(trim($this->params["search"]));
            $page = Paginator::buildPages($p, null, $search);
            $lots_list = LotFinder::searchByString($search, $page->getOffset());
            $data["search_string"] = $search;
        } else {
            $page = Paginator::buildPages($p);
            $lots_list = LotFinder::getAllOpened($page->getOffset());
        }


        $data["categories_equipment"] = $categories_list;
        $data["announcement_list"] = $lots_list;
        if ($page->total > 1) {
            $data["pages"] = $page;
        }

        $this->body_data = $data;

        $this->display();

    }

    public function default() 
    {

        $header_data["user"] = Authorization::getAuthData();
        $data["search_string"] = null;
        $data["category_id"] = null;
        $data["category_name"] = null;

        $categories_list = CategoryFinder::getAll();
        if (!empty($_REQUEST["page"])){
            $p = protectXSS($_REQUEST["page"]);
        } else {
            $p = 1;
        }

        
        $page = Paginator::buildPages($this->page);
        $lots_list = LotFinder::getAllOpened($page->getOffset());
        

        $data["categories_equipment"] = $categories_list;
        $data["announcement_list"] = $lots_list;
        if ($page->total > 1) {
            $data["pages"] = $page;
        }

        $footer_data = array (
            "categories_equipment" => $categories_list,
        );




        echo Templates::render("templates/header.php", $header_data);
        echo Templates::render($this->template, $data);
        echo Templates::render("templates/footer.php", $footer_data);

    }
}