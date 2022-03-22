<?php

define('DB_HOST', '192.185.2.151');
define('DB_NAME', 'ds2016_dhakasetup');
define('DB_USER', 'ds2016_user_ad');
define('DB_PASS', '@LexhlaEzPKF');
define('DB_CHAR', 'utf8');


class DB
{
    protected static $instance = null;

    protected function __construct() {}
    protected function __clone() {}

    public static function instance()
    {
        if (self::$instance === null)
        {
            $opt  = array(
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => FALSE,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET collation_connection=utf8_unicode_ci",
            );
            $dsn = 'mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset='.DB_CHAR;
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, $opt);
        }
        return self::$instance;
    }

    public static function __callStatic($method, $args)
    {
        return call_user_func_array(array(self::instance(), $method), $args);
    }

    public static function run($sql, $args = [])
    {
        if (!$args)
        {
             return self::instance()->query($sql);
        }
        $stmt = self::instance()->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }
}
$res = DB::run("SHOW VARIABLES LIKE 'character_set%';")->fetchAll();
echo '<table>';
foreach ($res as $key => $val) {
    echo '<tr><td>'.$val['Variable_name'].'</td><td>'.$val['Value'].'</td></tr>';
}
$res = DB::run("SHOW VARIABLES LIKE 'collation%';")->fetchAll();
foreach ($res as $key => $val) {
    echo '<tr><td>'.$val['Variable_name'].'</td><td>'.$val['Value'].'</td></tr>';
}
echo '</table>';
?>

<style>
table, th, td {
  border: 1px solid black;
}
</style>

