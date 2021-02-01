<?php

namespace MyApp\Models;

use MyApp\App;

class User
{
    const TABLE = "users";
    const TABLE_ROLES = "users_roles";
    const ADMIN_ROLE = 1;

    public static function loginExist($login)
    {
        $stmt = \MyApp\App::instance()->getDb()->getLink()
            ->prepare("SELECT * FROM users WHERE `login`=:login LIMIT 1");
        $stmt->bindParam(":login", $login, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);

    }

    public static function check($login, $pass)
    {
        $user = self::loginExist($login);
        return password_verify($pass, $user['pass']);
    }

    public static function signUp($login, $pass)
    {
        //add user
        $sql = App::instance()->getDb()->getLink()->
        prepare('INSERT INTO ' . self::TABLE . ' SET login=:login, pass=:pass');
        $sql->bindParam("login", $login, \PDO::PARAM_STR);
        $sql->bindParam("pass", $pass, \PDO::PARAM_STR);
        $sql->execute();
    }
    public static function isAdmin($loginId){
        return App::instance()->getDb()->getLink()->
        query('SELECT * FROM ' . self::TABLE_ROLES
            . ' WHERE user_id = ' . (int)$loginId
            .  ' and role = ' . self::ADMIN_ROLE)->
            fetch(\PDO::FETCH_ASSOC);

    }
}