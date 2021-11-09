<?php

// サニタイズを行う関数
function h($str) 
{
  return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

// データベースへ接続する関数
function getPdoInstance()
{
  try {
    $pdo = new PDO(
      DSN, 
      USER, 
      PASSWORD,
      [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
      ]
    );

  } catch (PDOException $e) {
    echo $e->getCode() . PHP_EOL;
    echo $e->getMessage() . PHP_EOL;
    exit;
  }

  return $pdo;
}