# CSV Sugar

A few helpers to make reading and writing CSV (and other delimited files)
a bit easier.

## A Quick Note on Line Endings

This library is a thin wrapper around `SplFileObject`, so all the caveats for
using that come into play here. This includes line endings. Your best best may
be to set `auto_detect_line_endings = On` in your `php.ini` or use...

```php
ini_set('auto_detect_line_endings', true)
```

## Reading CSV

```php
use PMG\CsvSugar\SimpleReader;
use PMG\CsvSugar\DictReader;

// pretty close to simply using SplFileObject
$reader = new SimpleReader('/path/to/file.csv');
foreach ($reader as $row) {
  // do stuff with $row
}

// Reading a CSV file into associative arrays with the first row of the file
// as the keys
$reader = new DictReader('/path/to/file.csv');
foreach ($reader as $row) {
  // $row['some_column'], etc
}

// Or you can tell DictReader some more about what you want to do
$reader = DictReader::builder('/path/to/file.csv')
  ->withFields(['one', 'two', 'three']) // the column names
  ->withRestKey('_extra_columns') // where to put the stuff from rows that are too long
  ->withRestValue('missing') // the value to put in on rows that are too short
  ->build();

/* the above is the same as...
$reader = new DictReader(
  '/path/to/file.csv',
  null, // dialect, see below
  ['one', 'two', 'three'], // fields
  '_extra_columns', // rest key
  'missing', // reset value
)
*/

foreach ($reader as $row) {
  // use $row['one'], etc
}
```

## Writing CSV

```php
use PMG\CsvSugar\SimpleWriter;
use PMG\CsvSugar\DictWriter;

// pretty close to plain old SplFileObject
$writer = new SimpleWriter('/path/to/file.csv');
// write a single row
$writer->writeRow(['one', 'two', 'three']);
// or write multiple rows, useful if you have an iterator to pass in here
$writer->writeRows([
    ['three', 'for', 'five'],
]);

// writeRow(s) can also take anything that implements `Traverable`
function makeRows() {
  foreach (range(0, 10) as $_) {
    yield new \ArrayIterator(range(1, 4));
  }
}

$writer->writeRow(new \ArrayIterator(['one', 'two', 'three']));
$writer->writeRows(makeRows());

// or use a dict writer to only output certain fields.

$writer = new DictWriter(
    '/path/to/file.csv',
    null, // dialect, see below
    ['one', 'two', 'three']
);
$writer->writeHeader(); // output the column names
$writer->writeRow([
    'one' => 1,
    'two' => 2,
    'three' => 3,
    'four' => 4, // ignored by default
]);

$writer->writeRows([
    [
        'one' => 1,
        'two' => 2,
        'three' => 3,
        'four' => 4, // ignored by default
    ],
]);

// want to be strict about it? Tell the writer to error when invalid
// keys are present
$writer = new DictWriter(
    '/path/to/file.csv',
    null, // dialect, see below
    ['one', 'two', 'three'],
    DictWriter::ERROR_INVALID
);
$writer->writeRow([
    'four' => 4, // will throw an exception
]);

// you can also tell `DictWriter` what you want to use when fields are missing
// we'll use a builder here since the constructor is getting a big unwieldy
$writer = DictWriter::builder('/path/to/file.csv')
    ->withFields(['one', 'two', 'three']) // fields to be output
    ->withExtraBehavior(DictWriter::ERROR_INVALID) // throw on invalid fields
    // default: ->withExtraBehavior(DictWriter::IGNORE_INVALID)
    ->withRestValue('missing') // defaults to an empty string
    ->build();

// 'two' and 'three' will be get filled in with 'missing'
$writer->writeRow([
    'one' => 1,
]);
```

## Dialects

A `Dialect` is a small value object representing how the CSV is written -- what
the delimiter, enclsoure, and escape character is. The default dialect is CSV
and all readers and writer take an (optional) `Dialect` objects as their second
constructor argument.

### Custom Dialects

```php
use PMG\CsvSugar\Dialect;

// $delimiter, $enclosure, $escapeCharacter
$dialect = new Dialect(',', "'", '\\');
```

### Named Constructors

```php
use PMG\CsvSugar\Dialect;

$csv = Dialect::csv();
$tabSeparated = Dialect::tsv();
$pipeSeparated = Dialect::pipe();
$tildeSeparated = Dialect::tilde();
```
