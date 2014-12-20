<?php
$output = shell_exec( 'cd /website/php && git reset --hard HEAD && git pull' );
echo $output;
