<?php

namespace MyApp\Models;

use MyApp\App;

class Table
{
    public static function getAll()
    {
        return App::instsnce()->getDB()->getLink()
            ->query("SELECT * FROM glossary_table")
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getHeaders()
    {
        $query = App::instsnce()->getDB()->getLink()
            ->query("show columns from glossary_table")
            ->fetchAll();
        $headers = [];
        foreach ($query as $item) {
            array_push($headers, $item[0]);
        }
        array_shift($headers);
        return $headers;
    }

    public static function addColumn($col_name)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("ALTER TABLE `glossary_table` ADD `$col_name` VARCHAR (5000) NOT NULL");
    }

    public static function deleteColumn($col_name)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("ALTER TABLE `glossary_table` DROP `$col_name`");
    }

    public static function editColumn($oldName, $name)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("ALTER TABLE `glossary_table` CHANGE `$oldName` `$name` VARCHAR(5000)");
    }

    public static function deleteTerm($name)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("DELETE FROM `glossary_table` WHERE `glossary_table`.`id` = $name");
    }

    public static function getTerm($id)
    {
        return App::instsnce()->getDB()->getLink()
            ->query("SELECT * FROM glossary_table WHERE id = $id")
            ->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function editTerm($id, $str)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("UPDATE `glossary_table` SET $str WHERE `glossary_table`.`id` = $id;");
    }

    public static function addTerm($names, $values)
    {
        return App::instsnce()->getDB()->getLink()
            ->exec("INSERT INTO `glossary_table` ($names) VALUES ($values)");
    }


}