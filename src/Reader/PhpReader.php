<?php

namespace Ambika\PhpParser\Reader;

use Illuminate\Http\Request;
use Ambika\PhpParser\Reader\ReaderService;
use Illuminate\Support\Facades\Storage;
use Ambika\PhpParser\Exceptions\FileNotFoundException;
use Ambika\PhpParser\Exceptions\InvalidFileFormatException;
use Ambika\PhpParser\Exceptions\UnableToReadFileException;
use Ambika\PhpParser\Exceptions\UnableToParseFileException;


class PhpReader extends ReaderService
{
	/**
	 * Validate file type and read file content as String
	 *
	 * @param string $path Path of the file
	 *
	 * @return array 
	 */
    protected function readContent($path)
    {
    	if (mime_content_type($path) !== 'text/x-php') {
    		throw new InvalidFileFormatException("Invalid File Type", 1);
    	}
    	try {    		
    		$content = file_get_contents($path);
    	} catch (Exception $e) {
    		throw new UnableToReadFileException("Unable to read file", 1);
    	}
        if (strpos($content, "defined('BASEPATH')")) {
           $content = $this->codeIgniter($path);
        }
        $contentValidation = $this->validateContent($content);
        if ($contentValidation) {
        	return $this->loadContent($path);        
        } 
        throw new UnableToParseFileException("Unable to parse file Content", 1);
	}

	/**
	 * This function will parse language file which are used in CodeIgniter
	 *
	 * @param string $fileLocation Path of the file
	 *
	 * @return String 
	 */
    protected function codeIgniter($fileLocation)
    {
        $string = file_get_contents($fileLocation);
        $string = str_replace("defined('BASEPATH') || exit('No direct script access allowed');", '', $string);
        $string = str_replace("||", '', $string);
        $string = str_replace("exit('No direct script access allowed');", '', $string);
        file_put_contents($fileLocation, ($string. "return \$lang;"));
        return file_get_contents($fileLocation);
    }

    /**
	 * This function will validate the content so that this can be include
	 *
	 * @param string $content File Content
	 *
	 * @return boolean 
	 */
	protected function validateContent($content)
	{
		$tokens = token_get_all($content);
		// Removimg white space
        $this->tokens = array_filter($tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_WHITESPACE);
        });
        // Removing comment from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_COMMENT);
        });
        // Removing php open tag from file(<?php)
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_OPEN_TAG);
        });
        // Removing return tag from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_RETURN);
        });
        // Removing array tag from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_ARRAY);
        });
        // Removing <?= or <%= tags from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_OPEN_TAG_WITH_ECHO);
        });
        // Removing doc comment from file (/** */)
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_DOC_COMMENT);
        });
        // Removing defind statement from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[1] !== 'defined');
        });
        // Removing T_BOOLEAN_OR (||) tags from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_BOOLEAN_OR);
        });
        // Removing exit or die statement from file
        $this->tokens = array_filter($this->tokens, function ($token) {
          return (! is_array($token) || $token[0] !== T_EXIT);
        });
       $parsingStatus = false;
       // Checking all the tokens are removed except T_CONSTANT_ENCAPSED_STRING, T_DOUBLE_ARROW, T_VARIABLE
        foreach ($this->tokens as $token) {
            if (is_array($token)) {
                if ((token_name($token[0]) === 'T_CONSTANT_ENCAPSED_STRING') || (token_name($token[0]) === 'T_DOUBLE_ARROW') || (token_name($token[0]) === 'T_VARIABLE')) {
                   $parsingStatus = true;
                } else {
                $parsingStatus = false;
                break;
            }
                
            } 
        }
        return $parsingStatus;
	}

	/**
	 * This function will load the file content
	 *
	 * @param string $path File Path
	 *
	 * @return array 
	 */
	protected function loadContent($path)
	{
        // If file contains only the specified tags then the file will be included.
		return include $path;
	}

}
