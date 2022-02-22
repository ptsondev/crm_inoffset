<?php



use Illuminate\Support\Facades\Log;

define('DB_HOSTNAME', 'localhost'); // database host name
define('DB_USERNAME', 'innhanhf_root'); // database user name
define('DB_PASSWORD', 'mysqlHaoilaHa'); // database password
define('DB_NAME', 'innhanhf_la8_crm'); // database name
function getDBH()
{
    $dsn = 'mysql:host=' . DB_HOSTNAME . ';dbname=' . DB_NAME;
    $options = array(
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    );
    $dbh = new PDO($dsn, DB_USERNAME, DB_PASSWORD, $options);
    return $dbh;
}



function display_number($num, $so0cuoi = 0)
{
    return number_format($num, $so0cuoi, '.', '.');
}
