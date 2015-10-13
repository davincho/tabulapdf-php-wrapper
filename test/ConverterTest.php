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
     * @var \Davincho\Tabula\Tabula
     */
    private $converter;

    public function setUp() {
        $file = __DIR__ . '/test_1.pdf';
        $this->converter = new \Davincho\Tabula\Tabula($file);
    }

    public function tearDown() {
        $this->converter = null;
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Process\Exception\InvalidArgumentException
     */
    public function shouldThrowExpcetionWhenNoFileSpecified() {
        $this->converter->setFile(null);
        $this->converter->parse();
    }

    /** @test */
    public function shouldParsePdfFile() {
        $result = $this->converter->parse();
        $lines = explode(PHP_EOL, $result);

        $this->assertEquals('1,2', $lines[0]);
        $this->assertEquals('9,10', $lines[4]);
    }

    /** @test */
    function shouldUseParametersAccordingly() {
        $output = tempnam(sys_get_temp_dir(), 'converter_test_');

        $result = $this->converter->parse([
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

        $result = $this->converter->parse([], $tempFile);
        $lines = explode(PHP_EOL, $result);

        $this->assertEquals('1,2', $lines[0]);
        $this->assertEquals('9,10', $lines[4]);
    }

    /**
     * @test
     * @expectedException \Symfony\Component\Process\Exception\RuntimeException
     */
    function shouldThrowExceptionWhenProcessFails() {
        $this->converter->parse(['--in', '--valid', '--arguments']);
    }

    /**
     * @test
     */
    function shouldUseExtraDirToLookForJavaExecutable() {

        $tmpPath = getenv('PATH');
        // Clear PATH environment variable and set bin dir explicitly
        putenv('PATH=');
        $this->converter->setBinDir(explode(';', $tmpPath));

        $result = $this->converter->parse();
        $lines = explode(PHP_EOL, $result);

        $this->assertEquals('1,2', $lines[0]);
    }
}