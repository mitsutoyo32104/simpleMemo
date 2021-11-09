<?php

require_once(__DIR__ . '/../app/config.php');

if (!isset($_SESSION['login'])) {
  header("Location: index.php");
  exit;
} else {
  // セッション変数には、index.phpのログインネーム($name)が入っている。
  $name = $_SESSION['login'];
}

try {
  require_once('../DBInfo.php');

  // データベース接続 
  $pdo = getPdoInstance();

  // usersテーブルから、$nameのレコードとIdとパスワードを取得。
  $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
  $stmt->bindValue('name', $name, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  $userId = $result['id'];


  // フォームから入力された値を取得
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim(filter_input(INPUT_POST, 'post-id'));
    $password = trim(filter_input(INPUT_POST, 'password'));
  }
 
  $pdo->beginTransaction();

  if ($title !== '' && password_verify($password, $result['password'])) {
    $sql = "DELETE FROM bbs WHERE user_id = :userId AND title = :title";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['userId' => $userId, 'title' => $title]);
  }

  $pdo->commit();
} 
catch (PDOException $e) {
  if(isset($pdo) == true && $pdo->inTransaction() == true) {
    $pdo->rollback();
  }
  echo $e->getCode();
  echo $e->getMessage() . PHP_EOL;
}
$pdo = null;

header('Location:bbs.php');