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
  // データベース接続\
  $pdo = getPdoInstance();

  // usersテーブルから、$nameのuser_idを取得。
  $stmt = $pdo->prepare("SELECT * FROM users WHERE name LIKE :name");
  $stmt->bindValue('name', $name, PDO::PARAM_STR);
  $stmt->execute();
  $result = $stmt->fetch();
  $userId = $result['id'];

  // 値の取得
  $keyword = trim(filter_input(INPUT_GET, 'keyword'));
  if (isset($keyword) && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $escKeyword = str_replace('%', '\%', $keyword);
    $search = "%$escKeyword%";

    // 検索フォーム入力時のクエリ発行
    $stmt = $pdo->prepare("SELECT * FROM bbs WHERE details LIKE :search AND user_id = :userId ORDER BY id DESC");
    $stmt->execute([':search' => $search, ':userId' => $userId]);
    $results = $stmt->fetchAll();

    // POSTでリダイレクトする。今回はなぜか必要なかった？
    //header('Location: bbs.php', true, 307);
  }
  else {
    //  検索フォームが空の場合のデータ一覧クエリ
    $stmt = $pdo->prepare("SELECT * FROM bbs WHERE user_id LIKE :userId ORDER BY id DESC");
    $stmt->execute([':userId' => $userId]);
    $results = $stmt->fetchAll();
  }
} 
catch (PDOException $e) {
  echo $e->getCode() . PHP_EOL;
  echo $e->getMessage() . PHP_EOL;
  exit;
}

$pdo = null;

?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <title>Simple Memo</title>
    <link rel="stylesheet" href="css/bbs.css" type="text/css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  </head>
  <body>
    <div id="container">
      <div id="main-title">
        <h1>Simple Memo</h1>
        <p><?= h($name) . ' さんようこそ！' ?></p>
        <p><a href="logout.php">ログアウトする</a></p>
      </div>
      <noscript>
        <p>JavaScriptを有効にして下さい。</p>
      </noscript>
      <div id="contents-wrapper">
      
        
          


        <div id="search-post">
          <div class="contents-title" id="search-title">
            <h3>メモの検索</h3>
            <img class="accordion-button" id="search-button" src="../../image/button-minus.png">
          </div>
          <div class="accordion-form" id="search-form">
            <p>次の項目を入力し、「検索」ボタンをクリックしてください。</p>
            <form action="bbs.php" memthod="get"> 
              <input class="form" type="search" name="keyword">を詳細メモに含む記事<br>
              <input type="submit" value="　検索　">
            </form>    
          </div>
        </div>



        <div id="new-post">
          <div class="contents-title"  id="post-title">
            <h3>新規メモの作成</h3>
            <img class="accordion-button" id="newpost-button" src="../../image/button-minus.png">
          </div>
          <div id="post-form" class="accordion-form">
          <p>次の項目を入力し、「追加」ボタンをクリックしてください。</p>
            <form action="bbs_insert.php" method="get">
              <label>タイトル　　　 <input class="form" type="text" name="name" required></label><br>
              <label for="main-category">カテゴリ　　　</label>
                <select class="form" id="main-category" name="main-category">
                  <option value="仕事">仕事</option>
                  <option value="私用">私用</option>
                  <option value="その他">その他</option>
                </select><br>
              <label for="sub-category">優先度　　　　 </label>
              <select class="form" id="sub-category" name="sub-category">
              </select><br>
                <label id="message-label">詳細なメモ　　 <textarea id="message" class="form" type="text" name="message" required>
                </textarea></label><br>
              <input type="submit" value="　追加　">
            </form> 
          </div>
        </div>



        <div id="delete-post">
          <div class="contents-title" id="delete-title">
            <h3>メモの削除</h3>
            <img class="accordion-button" id="delete-button" src="../../image/button-minus.png">
          </div>
          <div id="delete-form" class="accordion-form">
            <p>次の項目を入力し、「削除」ボタンをクリックしてください。</p>
            <p>(ログインパスワードが必要です)</p>
            <form action="bbs_delete.php" method="post">
              <label>メモのタイトル　
                <input class="form" type="text" name="post-id" id="post-id" 
                  title="半角数字で入力してください" required>
                <!-- 数字のみの入力の場合 → pattern="[0-9]+" -->
              </label>
              <br>
              <label>パスワード　　　
                <input class="form" type="password" name="password" 
                pattern="([A-Z]|[a-z]|[0-9]){4,8}" title="4~8文字以内の半角英数字で入力して下さい" required>
              </label>
              <br>
              <label><input id="indicate" type="checkbox" name="indicate">パスワードの表示</label><br>
              <input type="submit" value="　削除　" >
            </form>
          </div>
        </div>



        <div id="post-list">
          <h3 id="list-title">メモ一覧</h3>
          <div id="post-lists">
            <?php if ($results !== []): ?>
              <table>
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>タイトル</th>
                    <th>カテゴリ</th>
                    <th>優先度</th>
                    <th>詳細メモ</th>
                  </tr>
                </thead> 
                <tbody>
                  <?php foreach($results as $result): ?>
                    <tr>
                      <td><?= h($result['id']); ?></td>
                      <td><?= h($result['title']); ?></td>
                      <td><?= h($result['category']); ?></td>
                      <td><?= h($result['priority']); ?></td>
                      <td><?= nl2br(h($result['details'])); ?></td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            <?php else: ?>
              <p>検索条件に一致するメモはありません。</P>
            <?php endif; ?>
          </div>
        </div>
      </div>

      
      <footer>
        <p>@CPA All Rights Reserved</p>
      </footer>
    </div>
    <script type="text/javascript" src="js/bbs.js"></script>
  </body>
</html>