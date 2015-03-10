# clue/json-stream [![Build Status](https://travis-ci.org/clue/php-json-stream.svg?branch=master)](https://travis-ci.org/clue/php-json-stream)

A really simple and lightweight incremental streaming JSON parser

> Note: This project is in beta stage! Feel free to report any issues you encounter.

## Quickstart example

Once [installed](#install), you can use the following sample code to parse a stream of JSON chunks:

```php
$parser = new StreamingJsonParser();

assert($parser->push('[ 1, 2') === array());
assert($parser->push('3 ]') === array(array(1, 2, 3));
assert($parser->push('{} {}') === array(array(), array());
```

## Description

This is actually only a really simple hack to call a normal document based parser
whenever it *thinks* a full document has been found in the input stream.
Because the normal parser is implemented as an extension (instead of userland PHP),
this turns out to be pretty fast for streams that contain common, rather small
objects.

You might want to use this if

* you have to deal with a stream of multiple JSON documents
* you have to handle chunks of incomplete JSON documents
* you prefer a lightweight parser 

You probably don't want to use this if

* you deal with complete JSON documents
* you have a proper delimiter (such as newlines) between your individual JSON documents
* your JSON documents are too big to fit into RAM
* you have a CS background and/or are in love with actual incremental, recursive parsers

## Install

The recommended way to install this library is [through composer](http://getcomposer.org). [New to composer?](http://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "clue/json-stream": "~0.1.0"
    }
}
```

## License

MIT
