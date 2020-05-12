<?php
// 超出最大限制，会导致双向链表出错，限制一下就可以了。做一个警告
$t1 = time();
$cid = worker_go(function () {
	echo "before sleep" . PHP_EOL;
	worker_coroutine::sleep(0.01);
	echo "after sleep" . PHP_EOL;
});
$cid = worker_go(function () {
	echo "before sleep" . PHP_EOL;
	worker_coroutine::sleep(5);
	echo "after sleep" . PHP_EOL;
});
$cid = worker_go(function () {
	echo "before sleep" . PHP_EOL;
	worker_coroutine::sleep(10);
	echo "after sleep" . PHP_EOL;
});

echo "main co" . PHP_EOL;

worker_coroutine::scheduler();
return;

$serv = new worker_server("127.0.0.1", 8080);
var_dump($serv);
$sock = $serv->accept();
var_dump($sock);

while (1) {
	$connfd = $serv->accept();
	while (1) {
		$buf = $serv->recv($connfd);
		if ($buf == false) {
			break;
		}
		$serv->send($connfd, "hello");
	}
}
return;

worker_coroutine::sleep(1);
return;

function deferFunc1() {
	echo "in defer deferFunc1" . PHP_EOL;
}

function deferFunc2() {
	echo "in defer deferFunc2" . PHP_EOL;
}

function task($arg) {
	$cid = Workerman\Coroutine::getCid();
	echo "coroutine [$cid] create" . PHP_EOL;
	
	Workerman\Coroutine::defer("deferFunc" . $cid);
	
	worker_coroutine::yield();
	echo "coroutine [$cid] be resumed" . PHP_EOL;
}
$arr = array();
echo "main coroutine" . PHP_EOL;
$arr[] = worker_go('task', 'a');
echo "main coroutine" . PHP_EOL;
$arr[] = worker_go('task', 'b');
echo "main coroutine" . PHP_EOL;

// 恢復協程
foreach ($arr as $n) {
	if (Workerman\Coroutine::isExist($n)) {
		Workerman\Coroutine::resume($n);
		echo "main coroutine" . PHP_EOL;
	}
}