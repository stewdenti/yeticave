<?php

class BaseController 
{
    protected $params = null;
  
    protected $header_data = null;
    protected $body_data = null;
    protected $footer_data = null;


    public function __construct($params = null)
    {
        $this->params = $params;
    }

    public function default()
    {
       
    }

   public function display()
    {
        echo Templates::render("templates/header.php", $this->header_data);
        echo Templates::render("templates/main.php", $this->body_data);
        echo Templates::render("templates/footer.php", $this->footer_data);
    }

}