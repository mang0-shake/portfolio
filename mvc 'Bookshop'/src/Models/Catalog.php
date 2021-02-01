<?php

namespace MyApp\Models;

use MyApp\App;

class Catalog
{
    public const TABLE_CATEGORIES = "categories";
    public const TABLE_GOODS = "goods";

    public static function getCategories()
    {
        return App::instance()->getDb()->getTableData(self::TABLE_CATEGORIES);
    }

    public static function getCategoryById($id)
    {
        return App::instance()->getDb()->getById(self::TABLE_CATEGORIES, $id);
    }

    public static function getGoodsByCategory($id)
    {
        return App::instance()->getDb()->getlink()
            ->query("SELECT * FROM " . self::TABLE_GOODS . " WHERE category_id = " . (int )$id)
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getGoodById($id)
    {
        return App::instance()->getDb()->getById(self::TABLE_GOODS, $id);
    }

    public static function getGoodsByIds(array $ids): array
    {
        if(!count($ids)){
            return [];
        }
        return App::instance()->getDb()->getlink()
            ->query("SELECT * FROM " . self::TABLE_GOODS . " WHERE id IN (" .implode(', ',$ids).")")
            ->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function getGoodsForSlider($count)
    {
        return App::instance()->getDb()->getLink()
            ->query("SELECT * FROM " . self::TABLE_GOODS . " ORDER BY RAND() LIMIT " . $count)
            ->fetchAll(\PDO::FETCH_ASSOC);
    }
    public static function getAllProducts(){
        return App::instance()->getDb()->getLink()
            ->query("SELECT * FROM " . self::TABLE_GOODS)
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

}