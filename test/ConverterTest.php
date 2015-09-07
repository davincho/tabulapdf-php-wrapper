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

//    public function shouldParsePdfFile() {
//        $this->converter->parse()
//    }

}