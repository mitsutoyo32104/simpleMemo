<?php		
  // セッションスタート
  session_start();

  // サーバー接続用
  // const DSN = 'mysql:dbname=mitsu32104_mysql;host=mysql1.php.xdomain.ne.jp;port=3306';
  // const USER = 'mitsu32104_mysql';
  // const PASSWORD = '4Jamiro7Quai9';

  // Mac localhost用
  const DSN = 'mysql:dbname=simpleMemo;host=127.0.0.1;port=3306';
  const USER = 'root';
  const PASSWORD = 'mitsutoyo32104';

  require_once(__DIR__ . '/functions.php');