<?php

$AppMode = "test" ;  //dev-測試 , test-測試 , product--正式

//預設日期
define('DATE_START','2018-12-11');

/****************** 資料庫定義 ********************/
define ('SYSTEM_DBHOST','localhost');
define ('SYSTEM_DBNAME','id4956274_goldenbird');
define ('SYSTEM_DBUSER','id4956274_goldenbird');
define ('SYSTEM_DBPWD' ,'goldenbird');

/****************** 系統參數 ********************/
if (!isset($_SESSION)) session_start();

if($AppMode == "dev")
{
    // 报告所有错误
    error_reporting(E_ALL);
    //
    define('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird");
//    define('AutoloadAPPId','1947763301978389');
//    define('AutoloadAPPSecret','d6f451301cdbc78083712d574d55c201');
}
else if ($AppMode == "test")
{
    // 报告 runtime 错误
    error_reporting(E_ERROR | E_WARNING | E_PARSE);
    // 报告 E_NOTICE 之外的所有错误
    //error_reporting(E_ALL & ~E_NOTICE);
    //
    define ('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird");
//    define('AutoloadAPPId','1011631165676168');
//    define('AutoloadAPPSecret','7002699819af19678edf92f3790def4b');
}
else if ($AppMode == "product")
{
    // 关闭错误报告
    error_reporting(0);
    //
    define ('DOMAIN', $_SERVER['HTTP_HOST'] . "/goldenbird" );
//    define('AutoloadAPPId','1845375422218419');
//    define('AutoloadAPPSecret','1184f8ce3bd0b4cb440f1dd066f3ab26');
}
//define('FbADVersion','v3.2');
//
//
//define('MerchantID', '1292961');
//define('HashKey', '30GyZwBZjs2bgqDt');
//define('HashIV', 'HarKMTK6vPVM9Vvg');


//付款完成資料拋回
define('ReturnURL', DOMAIN. '/app/__returnurl.php?type=returnurl');
//建立訂單完成資料拋回
define('PaymentInfoURL', DOMAIN. '/app/__returnurl.php?type=paymentinfo');
//線上付款完成成功頁
define('OrderResultURL', DOMAIN. '/account/store_record/');
//取號完成顯示back button
define('ClientBackURL', DOMAIN. '/account/store_record/');


//21世紀API網址
//define('APP_API_URL', 'http://api.21-finance.com/app.aspx');

//
//function __autoload($className){
//    require_once('Controllers/'.$className.".php");
//}


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