<?php

class Main extends BaseController
{
    protected $page = 1;
        
    public function show() 
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;
        $this->header_data["user"] = Authorization::getAuthData();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();


        if (!$this->params) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];
            }

            if (!empty($this->params["category"])) {

                $pagination = Paginator::buildPages($this->page, $this->params["category"], null,  "/main/show");
                $lots_list = LotFinder::getByCategoryId(protectXSS($this->params["category"], $pagination->getOffset()));
                $current_category = CategoryFinder::getById($this->params["category"]);
                $this->body_data["category_id"] = $current_category->id;
                $this->body_data["category_name"] = $current_category->name;
                $this->body_data["announcement_list"] = $lots_list;
                if ($pagination->total > 1) {
                    $this->body_data["pages"] = $pagination;
                }
                $this->display();

            } else {
                // $pagination = Paginator::buildPages($this->page);
                // $lots_list = LotFinder::getAllOpened($pagination->getOffset());
                $this->default();
            }
        }
    }

    public function find()
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;
        $this->header_data["user"] = Authorization::getAuthData();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        if (!isset($_GET['find'])) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];

            } 
            if (!empty($_GET["search"])) {
                $search = protectXSS(trim($_GET["search"]));
                $pagination = Paginator::buildPages($this->page, null, $search, "/main/find");
                $lots_list = LotFinder::searchByString($search, $pagination->getOffset());
                $this->body_data["search_string"] = $search;
                $this->body_data["announcement_list"] = $lots_list;
                if ($pagination->total > 1) {
                    $this->body_data["pages"] = $pagination;
                }

                $this->display();
            } else {
                $this->default();
            }
        }


    }

    public function default() 
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;       

        if (!empty($this->params["page"])) {
           $this->page = (int)$this->params["page"];
        } 

        $pagination = Paginator::buildPages($this->page, null , null , $url = "/main/show");
        $lots_list = LotFinder::getAllOpened($pagination->getOffset());    

        if ($pagination->total > 1) {
             $this->body_data["pages"] = $pagination;
        }

        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->body_data["announcement_list"] = $lots_list;

        $this->header_data["user"] = Authorization::getAuthData();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        $this->display();
    }


    
}