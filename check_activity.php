<?php
session_start();

if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 60)) {
  // Nếu thời gian kể từ hoạt động cuối cùng của người dùng trên trang đã vượt quá 1 phút, thực hiện reload trang
  echo 'inactive';
  exit();
}

// Ghi nhận thời gian hoạt động mới nhất của người dùng trên trang
$_SESSION['last_activity'] = time();

echo 'active';
exit();
?>