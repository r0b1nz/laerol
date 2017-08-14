<?php

$str = "hey man what's u0p, my passwordi is abdc013 and SQL = 'or'=c'";
$pattern = '/[^\da-z]+/i';
echo preg_replace($pattern, ' ', $str);

?>