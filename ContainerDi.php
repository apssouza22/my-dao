<?php

namespace helpers;

class ContainerDi {

    private static function getDb() {
        $db = new \PDO("mysql:host=localhost;dbname=testedao", "root", "");
        return $db;
    }

    public static function getObject($name, $data = "") {
        if ($data)
            $objct = new $name(self::getDb(), $data[0], $data[1], $data[2]);
        else
            $objct= new $name(self::getDb());
        return $objct;
    }
}