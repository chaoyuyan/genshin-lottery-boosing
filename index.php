<?php
// 密码和对应的使用次数
$config = include 'mima.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if (!isset($config[$password])) {
        echo '密钥错误';
        exit;
    }

    if ($config[$password] <= 0) {
        echo '使用次数已达上限';
        exit;
    }

    // 更新使用次数
    $config[$password]--;
    file_put_contents('mima.php', '<?php return ' . var_export($config, true) . ';');

    // 生成随机数
   $num = mt_rand(1, 16);
//$data_arr = [ 2, 3, 4, 5, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16];
//$num = $data_arr[array_rand($data_arr)];
    // 记录日志
    $log = [
        'time' => date('Y-m-d H:i:s'),
        'password' => $password,
        'num' => $num,
    ];
    file_put_contents('log.txt', json_encode($log) . PHP_EOL, FILE_APPEND);

    echo '恭喜您获得了第 ' . $num . ' 号奖品';
}

// 抽奖页面
if (array_sum($config) <= 0) {
    echo '使用次数已达上限';
} else {
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>抽奖</title>
</head>
<body>
<center>
    <form method="post" action="">
        密钥：<input type="text" name="password"><br>
        <input type="submit" value="确认">
    </form>
1号是鱼叉一把，<br />
2号是晶核100，<br />
3号是任意角色材料168个<br />
</center>
</body>
</html>
<?php
}
