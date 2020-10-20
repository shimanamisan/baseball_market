<?php
mb_language("Japanese");
mb_internal_encoding("UTF-8");

$subject = "XAMPPから送信テスト";     // 題名
$body = "これはテストメールです。\n"; // 本文
$to = 'mikan.sup.all@gmail.com';          // 送信先

$result = mb_send_mail($to, $subject, $body);

if ($result){
    echo '成功';
}
else {
    echo '失敗';
}