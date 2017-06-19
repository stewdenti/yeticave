<?php

class BaseController 
{
    protected $template;

    protected static function getTemplate ()
    {
        return static::template
    }

    public function default()
    {
        Templates::render(self::getTemplate());
    }
}