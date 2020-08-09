<?php
    // testFunc.php

    function testFunc($val1, $val2){ // 割り算
        if($val2 == 0){
            throw new Exception("ゼロで除算しています"); // 例外処理
        }
        return $val1/$val2;
    }
    try{
        $num = testFunc(10, 0); // 0を入れてしまった！正しい引数を入れて試してみよう
        // 後続処理
        echo "{$num}";
    }catch(Exception $e){ 
        echo "エラー発生：" . $e->getMessage(); // メッセージ表示
        // ここでエラー回復処理
    }

    // 後続処理...
?>