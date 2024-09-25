<?php
$strings = ["apple", "banana", "fig", "orange", "kiwi"];

$lengths = array_map(function ($str) {
    return [$str, mb_strlen($str)];
}, $strings);

usort($lengths, function ($a, $b) {
    return $a[1] - $b[1];
});

$sortedStrings = array_map('current', $lengths);

print_r($sortedStrings);
?>


