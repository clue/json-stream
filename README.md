# clue/json-stream

[![CI status](https://github.com/clue/json-stream/workflows/CI/badge.svg)](https://github.com/clue/json-stream/actions)

A really simple and lightweight, incremental parser for [JSON streaming](https://en.wikipedia.org/wiki/JSON_Streaming)
(concatenated JSON and [newline-delimited JSON](http://ndjson.org/), in PHP.
You can use this library to process a stream of data that consists of multiple JSON documents.

**Table of contents**

* [Support us](#support-us)
* [JSON streaming](#json-streaming)
* [Quickstart example](#quickstart-example)
* [Description](#description)
* [Install](#install)
* [Tests](#tests)
* [License](#license)
* [More](#more)

## Support us

We invest a lot of time developing, maintaining and updating our awesome
open-source projects. You can help us sustain this high-quality of our work by
[becoming a sponsor on GitHub](https://github.com/sponsors/clue). Sponsors get
numerous benefits in return, see our [sponsoring page](https://github.com/sponsors/clue)
for details.

Let's take these projects to the next level together! 🚀

## JSON streaming

A newline-delimited JSON (NDJSON) example stream consisting of 3 individual JSON documents could look like this:

```json
{ "id": 1, "name": "first" }
{ "id": 3, "name": "third" }
{ "id": 6, "name": "sixth" }
```

> Less commonly, the same format is referred to as JSON lines (JSONL) or
  line-delimited JSON (LDJSON), which is not to be confused with JSON-LD.
  To avoid confusion, we consistently refer to this as newline-delimited JSON (NDJSON).
  If you control the generating side, we highly recommend going for NDJSON
  instead of using concatenated JSON as discussed below.
  See also [clue/reactphp-ndjson](https://github.com/clue/reactphp-ndjson).

For this project, the whitespace between the individual JSON documents is entirely optional.
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

This project does not currently follow [SemVer](https://semver.org/).
This will install the latest supported version:

```bash
$ composer require clue/json-stream:^0.1.1
```

See also the [CHANGELOG](CHANGELOG.md) for details about version upgrades.

This project aims to run on any platform and thus does not require any PHP
extensions and supports running on legacy PHP 5.3 through current PHP 8+ and
HHVM.
It's *highly recommended to use PHP 7+* for this project.

## Tests

To run the test suite, you first need to clone this repo and then install all
dependencies [through Composer](https://getcomposer.org/):

```bash
$ composer install
```

To run the test suite, go to the project root and run:

```bash
$ php vendor/bin/phpunit
```

## License

This project is released under the permissive [MIT license](LICENSE).

> Did you know that I offer custom development services and issuing invoices for
  sponsorships of releases and for contributions? Contact me (@clue) for details.

## More

* If you want to efficiently process (possibly infinite) streams of data,
  you may want to use [clue/reactphp-ndjson](https://github.com/clue/reactphp-ndjson)
  to process newline-delimited JSON (NDJSON) files (`.ndjson` file extension).
