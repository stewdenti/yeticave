<?php

class CategoryFinder extends BaseFinder 
{


    protected static function tableName()
    {
        return "categories";
    }

    protected static function entityName()
    {
        
        return "Category";
    }
    
}