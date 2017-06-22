<?php

class Main extends BaseController
{
    protected $page = 1;
        
    public function show() 
    {
        $this->body_data["category_id"] = null;
        $this->body_data["category_name"] = null;
        $this->body_data["search_string"] = null;


        if (!$this->params) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];
            }

            if (!empty($this->params["category"])) {

                $pagination = Paginator::buildPages($this->page, $this->params["category"]);
                $lots_list = LotFinder::getByCategoryId(protectXSS($this->params["category"], $pagination->getOffset()));
                $current_category = CategoryFinder::getById($this->params["category"]);
                $this->body_data["category_id"] = $current_category->id;
                $this->body_data["category_name"] = $current_category->name;
                $this->body_data["categories_equipment"] = CategoryFinder::getAll();
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

        if (!$this->params) {
            $this->default();
        } else {
            if (!empty($this->params["page"])) {
                $this->page = (int)$this->params["page"];
            } 
            if (!empty($this->params["search"])) {
                $search = protectXSS(trim($this->params["search"]));
                $pagination = Paginator::buildPages($this->page, null, $search);
                $lots_list = LotFinder::searchByString($search, $pagination->getOffset());
                $this->body_data["search_string"] = $search;



                $this->body_data["categories_equipment"] = CategoryFinder::getAll();
                $this->body_data["announcement_list"] = $lots_list;
                if ($page->total > 1) {
                    $this->body_data["pages"] = $pagination;
                }

                $this->display();
            } else {
                // $pagination = Paginator::buildPages($this->page);
                // $lots_list = LotFinder::getAllOpened($page->getOffset());
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

        $pagination = Paginator::buildPages($this->page);
        $lots_list = LotFinder::getAllOpened($pagination->getOffset());    

        if ($pagination->total > 1) {
             $this->body_data["pages"] = $pagination;
        }

        


        $this->body_data["categories_equipment"] = CategoryFinder::getAll();
        $this->body_data["announcement_list"] = $lots_list;

        $this->display();
    }


    public function display()
    {
        $this->header_data["user"] = Authorization::getAuthData();
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();
        




        echo Templates::render("templates/header.php", $this->header_data);
        echo Templates::render($this->template, $this->body_data);
        echo Templates::render("templates/footer.php", $this->footer_data);
    }
}