<?php

namespace MyApp;
class DB
{
    private $link;

    public function getLink(): \PDO
    {
        return $this->link;
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

        } catch (\PDOException $e) {
            die('Подключение не удалось: ' . $e->getMessage());
        }
    }

}