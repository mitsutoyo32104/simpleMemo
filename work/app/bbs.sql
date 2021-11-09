--  ログイン認証テーブル。
CREATE TABLE users (
  id INT NOT NULL AUTO_INCREMENT,
  name CHAR(40),
  password VARCHAR(100),
  PRIMARY KEY(id)
);

INSERT INTO users (name, password) VALUES
  ('noname', 'nopassword'),
  ('yamada', 'tarou'),
  ('sakura', 'hanako'),
  ('taguchi', 'hiroshi'),
  ('tanaka', 'kazuya');


-- 投稿テーブル
CREATE TABLE bbs (
  id INT NOT NULL AUTO_INCREMENT,
  user_id INT,
  title CHAR(40),
  category ENUM('仕事', '私用', 'その他'),
  priority ENUM('★★★', '★★', '★'),
  details VARCHAR(140),
  PRIMARY KEY(id),
  FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO bbs (user_id, title, category, priority, details) VALUES
  (1, 'test1', '仕事', '★★★', '山田さんに連絡'),
  (2, 'test2', '私用', '★★', '佐藤さんに連絡'),
  (3, 'test3', 'その他', '★', '鈴木さんに連絡'),
  (4, 'test4', '仕事', '★★', '斎藤さんに連絡'),
  (1, 'test1', '仕事', '★★★', '佐藤さんに連絡'),
  (2, 'test2', '私用', '★★', '田中さんに連絡'),
  (3, 'test3', 'その他', '★', '田島さんに連絡'),
  (4, 'test4', '仕事', '★★', '今村さんに連絡'),
  (1, 'test1', '仕事', '★★★', '山田さんに連絡'),
  (2, 'test2', '私用', '★★', '佐藤さんに連絡'),
  (3, 'test3', 'その他', '★', '鈴木さんに連絡'),
  (4, 'test4', '仕事', '★★', '斎藤さんに連絡'),
  (1, 'test1', '仕事', '★★★', '佐藤さんに連絡'),
  (2, 'test2', '私用', '★★', '田中さんに連絡'),
  (3, 'test3', 'その他', '★', '田島さんに連絡'),
  (4, 'test4', '仕事', '★★', '今村さんに連絡');