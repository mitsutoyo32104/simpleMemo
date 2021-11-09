<?php

require_once(__DIR__ . '/../app/config.php');

// セッションがなければログインページへ飛ばす
if (!isset($_SESSION['login'])) {
  header("Location: index.php");
  exit;
} else {
  // セッション変数には、index.phpのログインネーム($name)が入っている。
  $name = $_SESSION['login'];
}

try {  
  // データベース接続 
  $pdo = getPdoInstance();

  // usersテーブルから、$nameのuser_idを取得。
  $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
  $stmt->bindValue('name', $name, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  $userId = $result['id'];

  // 新規記事の値を取得
  $name = trim(filter_input(INPUT_GET, 'name'));
  $name = $name !== '' ? $name : '名無しさん';

  $mainCategory = trim(filter_input(INPUT_GET, 'main-category'));
  $mainCategory = isset($mainCategory) ? $mainCategory : 'None';

  $subCategory = trim(filter_input(INPUT_GET, 'sub-category'));
  $subCategory = isset($subCategory) ? $subCategory : 'None';

  $message = trim(filter_input(INPUT_GET, 'message'));
  $message = $message !== '' ? $message : 'メッセージがありません';

  // 新規書き込みのトランザクション処理
  $pdo->beginTransaction();
  
  // レコード挿入SQL
  $sql = "INSERT INTO bbs (
    user_id, title, category, priority, details)
  VALUES
    (:userId, :name, :mainCategory, :subCategory, :message)";

  $stmt = $pdo->prepare($sql);
  $stmt->execute([
    'userId' => $userId,
    ':name' => $name,
    ':mainCategory' => $mainCategory,
    ':subCategory' => $subCategory,
    ':message' => $message,
  ]);
  $pdo->commit();
} 
catch (PDOException $e) {
  if(isset($pdo) == true && $pdo->inTransaction() == true) {
    $pdo->rollback();
  }
  echo $e->getCode();
  echo $e->getMessage() . PHP_EOL;
  exit;
}
$pdo = null;

header('Location:bbs.php');
