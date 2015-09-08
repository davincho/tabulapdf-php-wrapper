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
     * @expectedException \Symfony\Component\Process\Exception\InvalidArgumentException
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
        $output = tempnam(sys_get_temp_dir(), 'converter_test_');

        $result = $this->converter->parse($file, [
            '-o', $output
        ]);

        $fileContents = file($output);

        $this->assertEquals('1,2', trim($fileContents[0]));
        $this->assertEquals('9,10', trim($fileContents[4]));
        $this->assertNull($result);

        unlink($output);
    }

    /** @test */
    function shouldWorkWithSpacesInFilename() {
        // Copy to file with blank in file name
        $tempFile = tempnam(sys_get_temp_dir(), 'converter') . ' 1';
        copy(__DIR__ . '/test_1.pdf', $tempFile);

        $result = $this->converter->parse($tempFile);
        $lines = explode(PHP_EOL, $result);

        $this->assertEquals('1,2', $lines[0]);
        $this->assertEquals('9,10', $lines[4]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Process\Exception\RuntimeException
     */
    function shouldThrowExceptionWhenProcessFails() {
        $file = __DIR__ . '/test_1.pdf';
        $this->converter->parse($file, ['in', 'valid', 'arguments']);
    }
}