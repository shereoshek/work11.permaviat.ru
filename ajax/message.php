<?php
session_start();
include("../settings/connect_datebase.php");


$IdUser = $_SESSION['user'];
$IdPost = $_POST["IdPost"];
$encryptedMessage = $_POST["Message"] ?? '';

function decryptAES($encryptedData, $key) {
    $data = base64_decode($encryptedData); 
    if ($data === false || strlen($data) < 17) {
        return false;
    }
    $iv = substr($data, 0, 16);
    $encrypted = substr($data, 16);
    $keyHash = md5($key);        
    $keyBytes = hex2bin($keyHash); 
    return openssl_decrypt($encrypted, 'aes-128-cbc', $keyBytes, OPENSSL_RAW_DATA, $iv);
}

$secretKey = "qwnkidokgmsdhkuenfsdj";
$Message = decryptAES($encryptedMessage, $secretKey);

if ($Message === false || $IdPost <= 0) {
    exit;
}

$Message = $mysqli->real_escape_string($Message);

$mysqli->query("INSERT INTO `comments`(`IdUser`, `IdPost`, `Messages`) VALUES ({$IdUser}, {$IdPost}, '{$Message}');");
?>