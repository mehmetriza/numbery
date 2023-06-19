<?php
require "vendor/autoload.php";





$a = "100,15TL";
$a = "100,15 TL";
$a = "$100,15 TL";
$a = "$100,15";

$a = "100TL";
$a = "100 TL";
$b = "100.855afs.555aaa";
$a = "$123,45 adam";

/*
echo Numbery::parse($a)
    ->decimal(2,true)
    ->decimalSeparator(',')
    ->thousandsSeparator('.')
    ->prefix('$',true)
    ->suffix(' adam',false)
    ->convert();
*/

var_dump(Numbery::parse("100")
->decimal(5,true)
->thousandsSeparator('.')
->convert()); 

var_dump(Numbery::parse("200")
->decimal(5,true)
->thousandsSeparator('.')
->convert()); 
