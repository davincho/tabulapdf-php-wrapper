<?php

/*
 * This file is part of tabulapdf-php-wrapper
 *
 * (c) https://github.com/davincho/tabulapdf-php-wrapper
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

class ConverterTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var \Davincho\Tabula\Converter
     */
    private $converter;

    public function setUp() {
        $this->converter = new \Davincho\Tabula\Converter();
    }

    public function tearDown() {
        $this->converter = null;
    }

    /**
     * @test
     * @expectedException \Davincho\Tabula\FileIsNullOrNotExistentException
     */
    public function shouldThrowExpcetionWhenNoFileSpecified() {
        $this->converter->parse();
    }

    /** @test */
    public function shouldParsePdfFile() {
        $file = __DIR__ . '/test_1.pdf';

        $result = $this->converter->parse($file);
        $lines = explode(PHP_EOL, $result);

        $this->assertEquals('1,2', $lines[0]);
        $this->assertEquals('9,10', $lines[4]);
    }

    /** @test */
    function shouldUseParametersAccordingly() {
        $file = __DIR__ . '/test_1.pdf';
        $output = sys_get_temp_dir() . '/tmp.txt';

        $result = $this->converter->parse($file, [
            '-o ' . $output
        ]);

        unlink($output);

        $this->assertNull($result);
    }
}