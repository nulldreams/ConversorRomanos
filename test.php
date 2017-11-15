<?php
require 'config.php';

$rom = new RomConverter();

echo '<pre>';
$rom->DecimalToRoman(3694);
echo '</pre>';
