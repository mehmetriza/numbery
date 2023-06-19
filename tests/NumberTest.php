<?php declare(strict_types=1);

use Exceptions\SuffixException;
use Exceptions\ThousandException;
use PHPUnit\Framework\TestCase;

final class NumberTest extends TestCase
{
    public function testSuccessFormat(): void
    {
        $string_number = "$100,15 m2";

        $result = Numbery::parse($string_number)
            ->decimal(2,true)
            ->decimalSeparator(',')
            ->thousandsSeparator('.')
            ->prefix('$',true)
            ->suffix(' m2',false)
            ->convert();

        $this->assertSame(100.15, $result);
    }
    
    public function testSuffixExceptionControl(): void
    {

        $this->expectException(SuffixException::class);
        
        $string_number = "100";

        $result = Numbery::parse($string_number)
            ->decimal(2,true)
            ->decimalSeparator(',')
            ->thousandsSeparator('.')
            ->prefix('$',true)
            ->suffix(' gram',false)
            ->convert();
    

    }
    public function testThousandExceptionControl(): void
    {

        $this->expectException(ThousandException::class);

        $string_number = "100.855,45 aaa";

        Numbery::parse($string_number)
            ->decimal(5,true)
            ->thousandsSeparator('.')
            ->convert(); 

    }
}
