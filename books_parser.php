<?php
/**
 * Created by PhpStorm.
 * User: dev
 * Date: 22.10.18
 * Time: 17:41
 */

$_userAgentList = array(
    "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)",
    "Mozilla/4.0 (compatible;)",
    "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.0.1) Gecko/2008070208",
    "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.0)",
    "Googlebot/2.1 (+http://www.google.com/bot.html)",
    "Mozilla/4.0 (compatible; MSIE 5.01; Windows NT 5.1)",
    "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7) Gecko/20040801 Firefox/0.9.0",
    "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.2.1) Gecko/20040803 Firefox/0.9.1",
    "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040821 Firefox/0.9.5",
    "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040821 Firefox/0.9.5",
    "Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.9.2.23) Gecko/20110920 Firefox/3.6.23",
    "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.52",
    "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.168 Version/11.51",
    "Opera/9.80 (Windows NT 5.09; U; ru) Presto/2.9.161 Version/11.50",
    "Opera/9.80 (Windows NT 5.1; U; ru) Presto/2.9.173 Version/11.52",
    "Mozilla/4.8 [en] (Windows NT 5.0; U)",
    "Opera/9.80 (S60; SymbOS; Opera Mobi/499; U; ru) Presto/2.4.18 Version/10.00",
    "Opera/9.60 (J2ME/MIDP; Opera Mini/4.2.14912/812; U; ru) Presto/2.4.15",
    "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Trident/5.0)",
//        "Android-x86-1.6-r2 - Mozilla/5.0 (Linux; U; Android 1.6; en-us; eeepc Build/Donut) AppleWebKit/528.5+ (KHTML, like Gecko) Version/3.1.2 Mobile Safari/525.20.1",
//        "Samsung Galaxy S - Mozilla/5.0 (Linux; U; Android 2.1-update1; ru-ru; GT-I9000 Build/ECLAIR) AppleWebKit/530.17 (KHTML, like Gecko) Version/4.0 Mobile Safari/530.17",
);

$servername = "mysql.positron";
$username = "root";
$password = "qwe";
$dbname = "positron";
$port = "3306";
$tableName = "book";


try{
    $conn = new PDO("mysql:host=$servername;port=$port;dbname=$dbname",$username,$password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    echo "Connected succesfully";
} catch(PDOException $e){
    echo "Connection failed: " . $e -> getMessage();
}

$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "https://gitlab.com/prog-positron/test-app-vacancy/-/raw/master/books.json",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_USERAGENT => $_userAgentList[0],
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "cache-control: no-cache",
        "postman-token: 3dd0b76b-c522-232c-0fbb-2d2056b0be36"
    ),
));
$response = curl_exec($curl);
$response = json_decode($response, true);
$err = curl_error($curl);
curl_close($curl);
if (!$err){
    foreach ($response as $item)
    {
        $attributes = implode(', ', array_keys($item));
        $values = beforeInsert($item);
        var_dump($item['isbn']);
        $keys = implode(', ', array_keys($values));
        $sql = "INSERT IGNORE INTO $tableName ($attributes) VALUES ($keys)";
        $result = $conn->prepare($sql)->execute($values);
        var_dump($result);
    }
} else {
    echo $err;
}

function beforeInsert($values)
{
    $toInsert = [];
    foreach ($values as $key => $value) {
        if ($key === 'publishedDate') {
            $value = reformatDate($value);
        }
        $toInsert[':'.$key] = is_array($value) ? implode(", ", $value) : $value;
    }
    return $toInsert;
}

function reformatDate($dateTime)
{
    $date = explode('T', $dateTime['$date']);
    $time = explode('.', $date[1])[0];
    $dateTime = $date[0].' '.$time;
    return $dateTime;
}