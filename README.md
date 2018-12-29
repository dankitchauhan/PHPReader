# PHPReader
This package will parse Php array files or language file's used in php for localization purpose.

# Quick Installation
```sh
$ composer require ambika/php-reader
```
### Simple example
```sh
$path = Storage::putFileAs('files', $file, $fileName);
$reader = new \Ambika\PhpParser\Reader\PhpReader();
return $reader->load(storage_path('app/'.$path));
```
This will return an key value pair of the file content.
##### If the content of the file is not valid then this will return an error.



