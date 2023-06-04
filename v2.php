<?php
date_default_timezone_set('Asia/Shanghai');
//$config = include 'mima.log';
$config_str = file_get_contents('mima.log');
$config = json_decode($config_str, true);
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'];
    if (!isset($config[$password])) {
     $_SESSION['message'] = '抽奖券不对哦';
	 header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }

    if ($config[$password] <= 0) {
    $_SESSION['message'] = '抽奖券用完了';
	header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }


    $config[$password]--;
  //  file_put_contents('mima.php', '<?php return ' . var_export($config, true) . ';');

$file_content = json_encode($config);
file_put_contents('mima.log', $file_content);


   //$num = mt_rand(1, 10);
$data_arr = [ 1, 2, 3, 5, 6, 7, 8, 9, 10 ];
$num = $data_arr[array_rand($data_arr)];

    $log = [
        'time' => date('Y-m-d H:i:s'),
        'password' => $password,
        'num' => $num,
    ];
    file_put_contents('log.txt', json_encode($log) . PHP_EOL, FILE_APPEND);

$_SESSION['message'] = '恭喜获得 ' . $num . ' 号奖品！';
header('Location: ' . $_SERVER['PHP_SELF']);
exit;
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta charset="UTF-8">
    <title>原神道具抽奖系统 - Genshin Lottery Boosing</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>
<center>
<p class="center">
<a href="https://afdian.net/item/44514facc3b611ed880452540025c377">获取抽奖券</a>
</p>
<?php 
 if (isset($_SESSION['message'])) {
        echo "<p><span>{$_SESSION['message']}</span></p>";
        unset($_SESSION['message']);
    }
?>

    <form method="post" action="">
  <input type="text" name="password" placeholder="输入抽奖券号码"><br>
        <input type="submit" value="试试运气">
    </form>
<div>
1号50晶核<br />
2号60晶核<br />
3号70晶核<br />
5号80晶核<br />
6号90晶核<br />
7号60原石(每日委托1次)<br />
8号金币树4颗<br />
9号经验树4颗<br />
10号鱼叉本体<br />
</div>
	
<div>
<?php 	
function display_log() {
	$log_file = 'log.txt';
  $log_data = file_get_contents($log_file);
  $log_array = explode("\n", $log_data);

  foreach ($log_array as $log_item) {
    $log_item = trim($log_item);
    if (empty($log_item)) continue;

    $log_obj = json_decode($log_item);
    $time = $log_obj->time;
    $password = $log_obj->password;
    $num = $log_obj->num;

    echo $time . ' ' . $password . ' 抽中 ' . $num . '号奖品<br>';
  }
}

display_log();

?>
</div>

</center>
</body>
</html>
