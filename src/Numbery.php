<?php

use Exceptions\DecimalException;
use Exceptions\NumericException;
use Exceptions\PrefixException;
use Exceptions\SuffixException;
use Exceptions\ThousandException;

class Numbery
{
    private static $instance;
    public static string $number;
    public static string $originalNumber;
    public static string $numberDecimal = "";
    public static string $numberThousand = "";

    private static $prefix;
    private static $prefixOptional;
    private static $suffix;
    private static $suffixOptional;
    
    private static $decimal;
    private static $decimalOptional;

    private static $decimalSeperator;
    private static $thousandsSeparator;

    public static function parse($number)
    {
        self::reset();
        self::$number = self::$originalNumber = $number;
        return new static;
    }
    static function decimal(int $number,bool $optional = false)
    {
        self::$decimal = $number;
        self::$decimalOptional = $optional;
        return new static;
    }
    static function decimalSeparator(string $seperator)
    {
        self::$decimalSeperator = $seperator;
        return new static;
    }
    static function thousandsSeparator(string $seperator)
    {
        self::$thousandsSeparator = $seperator;
        return new static;
    }
    static function prefix(string $prefix, bool $optional = false)
    {
        self::$prefix = $prefix;
        self::$prefixOptional = $optional;

        return new static;
    }
    static function suffix(string $prefix, bool $optional = false)
    {
        self::$suffix = $prefix;
        self::$suffixOptional = $optional;
        return new static;
    }
    private static function reset()
    {
        self::$number = self::$originalNumber = self::$suffix = self::$prefix = self::$decimal = self::$decimalOptional = self::$decimalSeperator = self::$thousandsSeparator = "";
    }
    private static function prefixControl()
    {
        if(self::$prefix) {
            $prefix = self::$prefix;
            //preg_match("/^".$prefix."/",self::$number,$matchData);
            $pattern = '/^('.preg_quote($prefix, "/") .').*/';
            preg_match($pattern, self::$number,$matchData);
            if (($matchData[1] ?? null) == $prefix) {
                self::$number = preg_replace('/^'.preg_quote($prefix, "/") .'(.*)/','$1',self::$number);
            } elseif(self::$prefixOptional == true && preg_match("/^\d+/", self::$number, $matchData)) {
                
            } else {
                throw new PrefixException("Prefix not work");
            }
        }
    }
    private static function suffixControl()
    {
        if(self::$suffix) {
            $suffix = self::$suffix;
            //preg_match("/^".$suffix."/",self::$number,$matchData);
            $pattern = '/.*('.preg_quote($suffix, "/") .')$/';
            preg_match($pattern, self::$number,$matchData);
            if (($matchData[1] ?? null) == $suffix) {
                self::$number = preg_replace('/(.*)'.preg_quote($suffix, "/") .'$/','$1',self::$number);
            } elseif(self::$suffixOptional == true && preg_match("/\d+$/", self::$number, $matchData)) {
                
            } else {
                throw new SuffixException(self::$originalNumber." - Suffix not work");
            }
        }
    }
    private static function decimalSeperatorControl()
    {
        $seperator = self::$decimalSeperator;
        if(self::$decimalSeperator){
            
            $is_match = preg_match("/".preg_quote($seperator, "/")."(\d+)$/",self::$number,$matchData);
            if ($is_match && is_numeric($matchData[1])) {
                if (self::$decimal >=0 && strlen($matchData[1]) != self::$decimal) {
                    throw new DecimalException("Decimal count incompatible!");
                }
                self::$numberDecimal = $matchData[1];
                self::$number = preg_replace("/(.*)".preg_quote($matchData[0],"/")."$/","$1",self::$number);
            } elseif (self::$decimalOptional == true && preg_match("/\d+$/", self::$number, $matchData)) {
                self::$numberDecimal = "";
            } else {
                throw new DecimalException(self::$number." Decimal seperator error!");
            }
        }
    }
    private static function thousandsSeperatorControl()
    {
        $seperator = self::$thousandsSeparator;
        if(self::$thousandsSeparator){
            $is_match = preg_match("/^(\d)+((".preg_quote($seperator, "/")."\d+)+)$/",self::$number,$matchData);
            
            if ($is_match && is_numeric($matchData[1]) && is_numeric(str_replace($seperator,"",$matchData[2]))) {
                
                self::$number = (int) $matchData[1].str_replace($seperator,"",$matchData[2]);
            } elseif (is_numeric(self::$number)) {
                
            } else {
                throw new ThousandException(self::$number." Thousands seperator error!");
            }
        }
    }
    public static function convert()
    {
        self::prefixControl();
        self::suffixControl();
        self::decimalSeperatorControl();
        self::thousandsSeperatorControl();
        
       


        if (!is_numeric(self::$number)) {
            throw new NumericException(self::$number." This number is not numeric.");
        }
        if (self::$numberDecimal) {
            $number = self::$number.".".self::$numberDecimal;
        } else {
            $number = self::$number;

        }
        
        if (!is_numeric($number)) {
            throw new NumericException($number." This number is not numeric.");
        }

        $number = (float) $number;
        return $number;
    }
}