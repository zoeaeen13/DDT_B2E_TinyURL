<?php
require_once('conn.php');
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
  case 'GET':
    handleGetURL();
    break;
  case 'POST':
    handlePOSTURL();
    break;
}


function handleGetURL() {
  global $conn;

  // handle error
  if (empty($_GET['code'])) {
    $json = array(
      "ok" => false,
      "message" => '找不到該網址或系統忙碌中',
    );

    $response = json_encode($json);
    echo $response;
    die();
  }
  $code = $_GET['code'];
  $sql = 'SELECT * FROM tiny_urls WHERE code = ?';
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("s", $code);
  $result = $stmt->execute();
  if (!$result) {
    $json = array(
      "ok" => false,
      "message" => $conn->error,
    );

    $response = json_encode($json);
    echo $response;
    die();
  }

  // successful
  $result = $stmt->get_result();
  $row = $result->fetch_assoc();

  $json = array(
    "ok" => true,
    "message" => 'success',
    "data" => $row['url'],
  );

  $response = json_encode($json);
  echo $response;
}


function handlePOSTURL() {
  global $conn;
  // handle error
  if (empty($_POST['url'])) {
    $json = array(
      "ok" => false,
      "message" => '請確認是否為有效網址或稍後再試',
    );

    $response = json_encode($json);
    echo $response;
    die();
  }

  $originUrl = $_POST['url'];
  $code = substr(md5(uniqid(rand(), true)),0,10);
  $sql = "INSERT INTO tiny_urls(code, `url`) VALUES(?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("ss", $code, $originUrl);
  $result = $stmt->execute();
  if (!$result) {
    $json = array(
      "ok" => false,
      "message" => $conn->error,
    );

  $response = json_encode($json);
  echo $response;
  die();
  }

  // successful
  $json = array(
    "ok" => true,
    "message" => "success",
    "data" => $code,
  );

  $response = json_encode($json);
  echo $response;
}
?>