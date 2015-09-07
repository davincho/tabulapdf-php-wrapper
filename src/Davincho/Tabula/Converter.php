<?php

/*
 * This file is part of tabulapdf-php-wrapper
 *
 * (c) https://github.com/davincho/tabulapdf-php-wrapper
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Davincho\Tabula;

use Symfony\Component\Process\Process;

class Converter
{
    /**
     * Default path to library
     * @var string
     */
    private $library = __DIR__ . '/../../../lib/tabula-extractor-0.7.4-SNAPSHOT-jar-with-dependencies.jar';

    /**
     * File to be converted (should be a PDF)
     * @var
     */
    private $file = null;

    /**
     * Converter constructor.
     * @param null $file
     */
    public function __construct($file = null)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    public function parse($file = null, $parameters = []) {
        $inputFile = $file ? $file : $this->file;
        $parameters = is_array($parameters) ? $parameters : [$parameters];

        if($inputFile === null || !file_exists($inputFile) || !is_readable($inputFile)) {
            throw new FileIsNullOrNotExistentException('File is null, not existent or not readable');
        }

        $arguments = ['java -jar', $this->library, implode(' ', $parameters), $file];

        $process = new Process(implode(' ', $arguments));
        $process->run();

        return $process->getOutput();
    }


}