<?php

namespace Ambika\PhpParser\Reader;

use Ambika\PhpParser\Exceptions\FileNotFoundException;

abstract class ReaderService
{
	/**
	 * Check and validate the file
	 *
	 * @param string $filePath File Path
	 *
	 * @return array 
	 */
	public function load($filePath)
	{
		if (!file_exists($filePath)) {
			throw new FileNotFoundException("File Not Found", 1);
		}
		return $this->readContent($filePath);
	}

	/**
	 * Validate file type and read file content as String
	 *
	 * @param string $path Path of the file
	 *
	 * @return array 
	 */
	abstract protected function readContent($filePath);

	/**
	 * This function will load the file content
	 *
	 * @param string $path File Path
	 *
	 * @return array 
	 */
	abstract protected function loadContent($content);
}
