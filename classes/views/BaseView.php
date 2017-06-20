<?php

class BaseView
{
    protected $header_template = "/templates/header.php";
    protected $body_template = null;
    protected $footer_template = "/templates/footer.php";

    public __construct($head = null, $main = null, $foot = null)
    {
        if ($head) {
            $this->header_data = $head;
        }

        if ($main) {
            $this->header_data = $head;
        }

        if ($foot) {
            $this->header_data = $head;
        }
    }

    public renderView() 
    {

    }

}


