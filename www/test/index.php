<?
    $th = new \PDO("mysql:host=data.knlab.kr;port=3306;dbname=politica;charset=utf8","crawl","Crawl12!@");
    $result=$th->prepare("desc crawler_info");
    $result->execute();
    print_r($result->fetch());
?>