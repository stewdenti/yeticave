<?php

class BaseController 
{
    protected $params = null;
    protected $user = null;
  
    protected $header_data = array();
    protected $body_data = array();
    protected $footer_data = array();


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