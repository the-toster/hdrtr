<?php

declare(strict_types=1);

$a = fn(int $x): int => $x;

var_dump(get_debug_type($a));