<?php

$y = array();
$y[0] = 2;
$y[1] = 10;
echo $y[0] . ' ' . $y[1];
exit();

$x = array('Pop' => ['abc', 'cde']);
echo $x[0][0];
exit();
foreach ($x as $key => $value) {
	echo $key . ' ' . $value[0];
}

exit();

$str = "hey man what's u0p, my passwordi is abdc013 and SQL = 'or'=c'";
$pattern = '/[^\da-z]+/i';
echo preg_replace($pattern, ' ', $str);

$x = array(1,2,3,4,5);
foreach ($x as $key => $value) {
	echo $key;
}

?>