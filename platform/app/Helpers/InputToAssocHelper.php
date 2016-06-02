<?php
namespace App\Helpers;

trait InputToAssocHelper {
    public function collectionToAssoc (array $collection, $key, $value):array {
        $assoc = [];
        
        foreach ($collection as $row) {
            if (isset($row->{$key})){
                $assoc[$row->{$key}] = isset($row->{$value}) ? $row->{$value} : NULL;
            } else {
                throw new \Exception("key not set!");
            }
        }
        
        return $assoc;
    }
}