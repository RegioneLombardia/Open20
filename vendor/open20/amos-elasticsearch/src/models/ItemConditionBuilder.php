<?php
namespace open20\elasticsearch\models;


class ItemConditionBuilder 
{
    
   public static function rangeItem()
   {
       return new ItemConditionRange();
   }
   
   public static function Item() 
   {
        return new ItemCondition();
   }
}
