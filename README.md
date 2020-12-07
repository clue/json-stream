# clue/json-stream [![Build Status](https://travis-ci.org/clue/php-json-stream.svg?branch=master)](https://travis-ci.org/clue/php-json-stream)

A really simple and lightweight, incremental parser for [JSON streaming](https://en.wikipedia.org/wiki/JSON_Streaming)
(concatenated JSON and [line delimited JSON](https://en.wikipedia.org/wiki/Line_Delimited_JSON)), in PHP.
You can use this library to process a stream of data that consists of multiple JSON documents.

A line delimited JSON example stream consisting of 3 individual JSON documents could look like this:

```json
{ "id": 1, "name": "first" }
{ "id": 3, "name": "third" }
{ "id": 6, "name": "sixth" }
```

The whitespace between the individual JSON documents is optional.
Instead of newlines, you can use any number of whitespace or none at all.

A concatenated JSON example stream consisting of 3 individual JSON documents could look like this:
```json
{ "id": 1, "name": "first" }{ "id": 3, "name": "third" }{ "id": 6, "name": "sixth"}
```

The input stream can be of arbitrary size and can be interrupted at any time.
This is often useful for processing network streams, where the chunk/buffer size is
not under your control and you could potentially read single bytes only.

Please note that this library is about processing a stream that can contain any number of
JSON documents.
It is assumed that each document has a reasonable size and fits into memory.
This is not to be confused with a streaming parser for processing a single, huge JSON document
that is too big to fit into memory.

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

The recommended way to install this library is [through Composer](https://getcomposer.org/).
[New to Composer?](https://getcomposer.org/doc/00-intro.md)

```JSON
{
    "require": {
        "clue/json-stream": "~0.1.0"
    }
}
```

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org):

```bash
$ composer install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## License

MIT
