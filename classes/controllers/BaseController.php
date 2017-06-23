<?php

class BaseController 
{
    protected $params = null;
    protected $user = null;
  
    protected $header_data = null;
    protected $body_data = null;
    protected $footer_data = null;


    public function __construct($params = null)
    {   

        $this->params = $params;
        $this->authenticate();
    }

    public function authenticate()
    {
        $this->user = Authorization::getAuthData();

    }

   public function display($body_template)
    {   
        $this->header_data["user"] = $this->user;
        $this->footer_data["categories_equipment"] = CategoryFinder::getAll();

        echo Templates::render("templates/header.php", $this->header_data);
        echo Templates::render($body_template, $this->body_data);
        echo Templates::render("templates/footer.php", $this->footer_data);
    }

}