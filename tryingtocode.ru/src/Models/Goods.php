<?php

namespace MyApp\Models;

class Goods
{
    public static function getAll()
    {
        return \MyApp\App::instance()->getDb()->getTableData("goods");
    }
}