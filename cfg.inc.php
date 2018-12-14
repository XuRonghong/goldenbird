<?php

$AppMode = "product" ;  //dev-測試 , test-測試 , product--正式

//預設日期
define('DATE_START','2018-12-11');

/****************** 系統參數 ********************/
if (!isset($_SESSION)) session_start();

if($AppMode == "dev")
{
    /****************** 資料庫定義 ********************/
    define ('SYSTEM_DBHOST','localhost');
    define ('SYSTEM_DBNAME','id4956274_goldenbird');
    define ('SYSTEM_DBUSER','id4956274_goldenbird');
    define ('SYSTEM_DBPWD' ,'goldenbird');
    /****************** 資料庫定義 ********************/
    // 报告所有错误
    error_reporting(E_ALL);
    //
    define('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird");
}
else if ($AppMode == "test")
{
    /****************** 資料庫定義 ********************/
    define ('SYSTEM_DBHOST','localhost');
    define ('SYSTEM_DBNAME','id4956274_goldenbird');
    define ('SYSTEM_DBUSER','id4956274_goldenbird');
    define ('SYSTEM_DBPWD' ,'goldenbird');
    /****************** 資料庫定義 ********************/
    // 报告 runtime 错误
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    // 报告 E_NOTICE 之外的所有错误
    //error_reporting(E_ALL & ~E_NOTICE);
    //
    define ('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird");
}
else if ($AppMode == "product")
{
    /****************** 資料庫定義 ********************/
    define ('SYSTEM_DBHOST','localhost');
    define ('SYSTEM_DBNAME','id8154279_goldenbird');
    define ('SYSTEM_DBUSER','id8154279_goldenbird');
    define ('SYSTEM_DBPWD' ,'goldenbird');
    /****************** 系統參數 ********************/
    // 关闭错误报告
    error_reporting(0);
    //
    define ('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird" );
}


function __autoload($className){
    require_once('Controllers/'.$className.".php");
}


if (! function_exists('dd')) {
    /**
     * Dump the passed variables and end the script.
     *
     * @param  mixed $args
     * @return void
     */
    function dd($args = [])
    {
        http_response_code(500);
        if (is_array($args)) {
            foreach ($args as $key => $x) {
                echo '<blockquote>';
                if (is_array($x)) {
                    foreach ($x as $key2 => $xx) {
                        echo '<blockquote>';
                        echo $key2.' => ';
                        var_dump($xx);
                        echo '</blockquote>';
                    }
                } elseif ($x) {
                    echo $key.' => ';
                    var_dump($x);
                }
                echo '</blockquote>';
            }
        } elseif ($args) {
            var_dump($args);
        }
        die(1);
    }
}
?>