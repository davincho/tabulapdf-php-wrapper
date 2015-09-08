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

use Symfony\Component\Process\Exception\InvalidArgumentException;
use Symfony\Component\Process\Exception\RuntimeException;
use Symfony\Component\Process\ProcessBuilder;

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
            throw new InvalidArgumentException('File is null, not existent or not readable');
        }

        $processBuilder = new ProcessBuilder();
        $processBuilder->setPrefix('java')
            ->setArguments(array_merge(['-jar', $this->library, $file], $parameters));

        $process = $processBuilder->getProcess();
        $process->run();

        if(!$process->isSuccessful()) {
            throw new RuntimeException($process->getErrorOutput());
        }

        return $process->getOutput();
    }


}