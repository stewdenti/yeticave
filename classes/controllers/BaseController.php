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


}