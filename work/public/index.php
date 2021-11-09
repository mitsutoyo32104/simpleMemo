<?php

require_once(__DIR__ . '/../app/config.php');

// sessionがあれば、bbs.phpへ飛ばす
if(isset($_SESSION['login'])) {
  session_regenerate_id(TRUE);
  header("Location: bbs.php");
  exit();
}

// 入力フォームより値を取得。
$name = trim(filter_input(INPUT_POST, 'name'));
// ここでパスワードハッシュで暗号化すべき
$password = trim(filter_input(INPUT_POST, 'password'));


// 入力フォームが入力されていれば、login/registerで分岐
if($_SERVER['REQUEST_METHOD'] === 'POST' && $name !== '' && $password !== '') {
  $action = filter_input(INPUT_GET, 'action');

  try {
    // データベース接続 
    $pdo = getPdoInstance();

    switch ($action) {
      // ログイン処理
      case 'login':
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name=:name");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();
      
        // パスワードの照合とセッションIDと変数の作成
        if (password_verify($password, $result['password']) && $result['name'] === $name) {
          session_regenerate_id(TRUE);
          $_SESSION["login"] = $name;

          header('Location: bbs.php');
          exit;
        } 
        else {
          header('Location: index.php?loginMessage=※ログイン情報が正しくありません。');
          exit;
        }
        break;

      // 新規登録処理                 
      case 'register':
        // 既存のデータベースからnameを照合
        $stmt = $pdo->prepare("SELECT * FROM users WHERE name = :name");
        $stmt->bindValue('name', $name, PDO::PARAM_STR);
        $stmt->execute();
        $result = $stmt->fetch();

        // もし既存のデータベースに同じ名前が存在しないなら登録する。
        if (empty($result)) {
          $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
          $stmt = $pdo->prepare("INSERT INTO users (name, password) VALUES (:name, :password)");
          $stmt->bindValue('name', $name, PDO::PARAM_STR);
          $stmt->bindValue('password', $hashedPassword, PDO::PARAM_STR);
          $stmt->execute();

          header('Location: index.php?loginMessage=※登録完了!ログインして下さい。');
          exit;
        }
        else {
          header("Location: index.php?registerMessage=※そのログインネームは既に存在しています。");
          exit;
        }
        break;
    }
  } 
  catch (PDOException $e) {
    exit('database error');
  }
} else {
  $loginMessage = 'ログインはこちら';
}

$pdo = null;

?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/index.css">
  </head>
  <body>
    <div class="container">
      <header>

      </header>
      <main>
        <div class="login">
        <h1>Simple Memo</h1>
        <p><?= h($loginMessage) ?></p>
        <p class="caution">
          <?php if(isset($_GET['loginMessage'])): ?>
            <?= h($_GET['loginMessage']) ?>
          <?php endif; ?> 
        </p>
          <form class="login-form" action="?action=login" method="post">
            <input type="text" name="name" placeholder="user-name" required> 
            <input type="password" name="password" placeholder="password" required>
            <button>ログイン</button>
          </form>
        </div>
        <div class="register">
          <p>新規登録はこちら</P>
          <p class="caution">
            <?php if(isset($_GET['registerMessage'])): ?>
              <?= h($_GET['registerMessage']) ?>
            <?php endif; ?> 
          </p>
          <form class="login-form" action="?action=register" method="post">
            <input type="text" name="name" placeholder="user-name" required>
            <input type="password" name="password" placeholder="password" required>
            <button>登録する</button>
          </form>
        </div>
      </main>
    </div>
  </body>
</html>
