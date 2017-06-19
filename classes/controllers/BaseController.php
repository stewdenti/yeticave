<?php

class BaseController 
{
    protected $template;
    protected $header_data;
    protected $main_data;
    protected $footer_data;


    protected function getTemplate ()
    {
        return $this->template;
    }


    public function __construct($params = null)
    {
        
    }

    public function default()
    {
        Templates::render($this->getTemplate());
    }


}