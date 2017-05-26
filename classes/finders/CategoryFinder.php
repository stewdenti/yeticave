<?php

class CategoryFinder extends BaseFinder 
{
    /**
     * возвращает имя таблицы
     *
     * @return string имя таблицы
     */
    protected static function tableName()
    {
        return "categories";
    }
    
    /**
     * возвращает имя класса
     *
     * @return string имя класса
     */
    protected static function entityName()
    {
        
        return "Category";
    }
    
}