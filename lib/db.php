<?php
date_default_timezone_set("Europe/Moscow");

$table_requests = "partner_request";
$table_company = "company";                    

function getConnect(): PDO {

    $db_host = "localhost"; 
    $db_user = "root";
    $db_password = "qAz321_mKL";
    $db_base = 'form-send';
    $db_charset = 'utf8'; 

    return new PDO("mysql:host=$db_host;dbname=$db_base;charset=$db_charset", $db_user, $db_password);
}

function isSetPostVars(array $vars): bool {
    foreach ($vars as $item) {
        if (!isset($_POST[$item])) {
            return false;
        }
    }
    return true;
}

function getPostVars(array $vars): array {
    $result = [];
    foreach ($vars as $item) {
        $result[$item] = trim($_POST[$item]);
    }
    return $result;
}

?>