<?php

class Main extends BaseController
{

    protected $template = "templates/main.php";
    
    public function show() 
    {
        
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

        
        $page = Paginator::buildPages($p);
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