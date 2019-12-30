<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Ambika\PhpParser\Reader\PhpReader;
use Ambika\PhpParser\Exceptions\FileNotFoundException;
use Ambika\PhpParser\Exceptions\InvalidFileFormatException;
use Ambika\PhpParser\Exceptions\UnableToParseFileException;

class ParsingTest extends TestCase
{
  /**
   * A Simple php array file parsing test
   *
   * @test
   *
   * @return void
   */
  public function parseSimpleArrayFile()
  {
    $reader = new PhpReader();
    $path = __DIR__ . '/Mock/sample_2.php';
    $response = $reader->load($path);
    $this->assertEquals(array('foo' => 'bar'), $response);
  }

  /**
   * A Laravel php language file parsing test
   *
   * @test
   *
   * @return void
   */
  public function parseLaravelLanguageFile()
  {
    $reader = new PhpReader();
    $path = __DIR__ . '/Mock/sample_1.php';
    $response = $reader->load($path);
    $this->assertEquals(
      array(
        "foo" => array(
          "foo1" => array(
            "foo2" => "Lorem ipsum dolar input.",
            "foo3" => "Lorem ipsum dolar input.",
            "foo4" => "Lorem ipsum dolar input."
          ),
          "foo5" => array(
            "foo6" => "Lorem ipsum dolar input.",
            "foo7" => "Lorem ipsum dolar input.",
            "foo8" => "Lorem ipsum dolar input.",
            "foo9" => "Lorem ipsum dolar input.",
            "foo10" => "Lorem ipsum dolar input.",
            "foo11" => "Lorem ipsum dolar input.",
            "foo12" => "Lorem ipsum dolar input.",
            "foo13" => "Lorem ipsum dolar input.",
            "foo14" => "Lorem ipsum dolar input",
            "foo15" => "Lorem ipsum dolar input",
            "foo16" => "Lorem ipsum dolar input.",
            "foo17" => "Lorem ipsum dolar input."
          )
        ),
        "foo18" => array(
          "foo19" => array(
            "foo20" => "Lorem ipsum dolar input, Lorem ipsum dolar input,Lorem ipsum dolar input."
          )
        )
      ),
      $response
    );
  }


  /**
   * A CodeIgniter language file parsing test
   *
   * @test
   *
   * @return void
   */
  public function parseCodeIgniterLanguageFile()
  {
    $reader = new PhpReader();
    $path = __DIR__ . '/Mock/sample_3.php';
    $response = $reader->load($path);
    $this->assertEquals(
      array(
        "foo" => "Lorem ipsum dolar input",
        "foo1" => "Lorem ipsum dolar input",
        "foo2" => "Lorem ipsum dolar input.",
        "foo3" => "Lorem ipsum dolar input",
        "foo4" => "Lorem ipsum dolar input",
        "foo5" => "Lorem ipsum dolar input",
        "foo6" => "Lorem ipsum dolar input <em>lorem</em> Lorem ipsum dolar input:",
        "foo7" => "Lorem ipsum dolar input",
        "foo8" => "Lorem ipsum dolar input",
        "foo9" => "Lorem ipsum dolar input",
        "foo10" => "Lorem ipsum dolar input",
        "foo11" => "Lorem ipsum dolar input",
        "foo12" => "Lorem ipsum dolar input."
      ),
      $response
    );
  }

  /**
   * A sample test for Testing FileNotFoundException
   *
   * @test
   *
   *@return void
   */
  public function fileNotFoundExceptionTest()
  {
    $reader = new PhpReader();
    $this->expectException(FileNotFoundException::class);
    $path = __DIR__ . '/Mock/sample_2.php';
    $reader->load($path);
  }

  /**
   * A sample test for Testing FileNotFoundException
   *
   * @test
   *
   *@return void
   */
  public function invalidFileFormatExceptionTest()
  {
    $reader = new PhpReader();
    $this->expectException(InvalidFileFormatException::class);
    $path = __DIR__ . '/Mock/sample_4.php';
    $reader->load($path);
  }

  /**
   * A sample test for Testing FileNotFoundException
   *
   * @test
   *
   *@return void
   */
  public function unableToParseFileExceptionTest()
  {
    $reader = new PhpReader();
    $this->expectException(UnableToParseFileException::class);
    $path = __DIR__ . '/Mock/sample_5.php';
    $reader->load($path);
  }
}
