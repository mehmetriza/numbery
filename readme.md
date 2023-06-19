
### Php string to number and validation convert function
<br>

# install
    composer require mehmetriza/numbery

# Usage
```php
require "vendor/autoload.php";


    Numbery::parse("1.000,00 €") // string number 
        ->decimal(2,true) // decimal count, optional (true|false)
        ->decimalSeparator(',') // using decimal operator
        ->thousandsSeparator('.') // thousand seperator chracter
        ->prefix('$',true) // prefix chracter, is optional (true|false)
        ->suffix(' adam',false) // suffix chracter, is optional (true|false)
        ->convert(); //return double
```
<br>
<br>

## Example 1
<br>

```php
    $a = '$123,45 adam';

    Numbery::parse($a)
        ->decimal(2,true) 
        ->decimalSeparator(',') 
        ->thousandsSeparator('.') 
        ->prefix('$',true) 
        ->suffix(' adam',false)
        ->convert();

    // return 123.45 -> double
```
<br>

## Example 2

<br>

```php
$a = "100.855.555";

Numbery::parse($a)
    ->decimal(5,true)
    ->thousandsSeparator('.')
    ->convert();

// return 100855555 -> double
```

 if you want the return data type integer

```php


$value = int Numbery::parse($a)
    ->decimal(5,true)
    ->thousandsSeparator('.')
    ->convert();
```


## Exceptions

    throws an error if it doesn't conform to conditions

```php 
new NumericException;
new DecimalException;
new PrefixException;
new SuffixException;
new ThousandException;
```
