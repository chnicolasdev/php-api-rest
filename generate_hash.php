<?php

$time = time();
echo "Time: $time".PHP_EOL."Hash: ".sha1($argv[1].$time.'Sh! no se lo cuentes a nadie').PHP_EOL;