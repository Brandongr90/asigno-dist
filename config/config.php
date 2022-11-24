<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=utf-8");
header('Access-Control-Allow-Headers: *');
class Config
{
    protected $db;
    protected function connect()
    {
        try {
            $NAMEDB = 'heroku_3961ccb6d887274';
            $HOST = 'us-cdbr-east-06.cleardb.net';
            $USER = 'becceebb7b51b8';
            $PASSWORD = '92e3e329';
            $conectar = $this->db = new PDO("mysql:host=$HOST;dbname=$NAMEDB", "$USER", "$PASSWORD");
            return $conectar;
        } catch (Exception $e) {
            print "¡Error BD!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    public function set_names()
    {
        return $this->db->query("SET NAMES 'utf8'");
    }
}
