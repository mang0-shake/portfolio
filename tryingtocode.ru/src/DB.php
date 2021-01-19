<?php

namespace MyApp;

class DB
{
    private $link;

    public function getLink(): \PDO
    {
        return $this->link;
    }

//    public function show()
//    {
////        return "show piu";
//        return $this->link->query("select * from goods")->fetchAll();
//    }

    public function getTableDataCount($tableName)
    {
        try {
            return $this->link
                ->query("SELECT COUNT(*) FROM {$tableName}")
                ->fetchColumn();
        } catch (\Throwable $e) {
            return null;
        }
    }

//    public function getTableDataPart($tableName, int $from, int $limit)
//    {
//        try {
//            return $this->link
//                ->query("SELECT * FROM {$tableName} LIMIT {$from},{$limit}")
//                ->fetchAll(\PDO::FETCH_ASSOC);
//        } catch (\Throwable $e) {
//            return null;
//        }
//    }

    public function getTableData($tableName)
    {
        try {
            return $this->link
                ->query("SELECT * FROM {$tableName}")
                ->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function getById($tableName, $id)
    {
        try {
            return $this->link
                ->query("SELECT * FROM {$tableName} WHERE id = " . (int)$id)
                ->fetch(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public function __construct($config)
    {
        try {
            $this->link = new \PDO(
                $config['dsn'],
                $config['user'],
                $config['pwd'],
                [\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION]
            );
//            $this->link->set_charset('utf8');
        } catch (\PDOException $e) {
//            echo phpinfo();
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }
}