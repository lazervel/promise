# PHP Promise

<p align="center">
<a href="https://github.com/shahzadamodassir"><img src="https://img.shields.io/badge/Author-Shahzada%20Modassir-%2344cc11?style=flat-square"/></a>
<a href="LICENSE"><img src="https://img.shields.io/github/license/lazervel/promise?style=flat-square"/></a>
<a href="https://packagist.org/packages/modassir/promise"><img src="https://img.shields.io/packagist/dt/modassir/promise.svg?style=flat-square" alt="Total Downloads"></img></a>
<a href="https://github.com/lazervel/promise/stargazers"><img src="https://img.shields.io/github/stars/lazervel/promise?style=flat-square"/></a>
<a href="https://github.com/lazervel/promise/releases"><img src="https://img.shields.io/github/release/lazervel/promise.svg?style=flat-square" alt="Latest Version"></img></a>
</p>

## Composer Installation

Installation is super-easy via [Composer](https://getcomposer.org)

```bash
composer require modassir/promise
```

or add it by hand to your `composer.json` file.

## Getting Started

```php
use Modassir\Promise\Promise;

(new Promise(function($resolve, $reject) {
  $resolve('success');
  $reject('An error accurred.');
}))->then(function($res) {
  // echo : 'success'
})->catch(function() {

})->finally(function() {
  // echo: strtoupper('Hello Worlds!');
});

(new Promise(function($resolve, $reject) {
  $reject('An error accurred.');
  $resolve('success');
}))->then(function() {

})->catch(function($res) {
  // echo: 'An error accurred.'
})->finally(function() {
  // echo: strtoupper('Hello Worlds!');
});

(new Promise(function($resolve, $reject) {
  $resolve('success');
  $reject('An error accurred.');
  call_undfined_function();
}))->then(function() {

})->catch(function($res) {
  $res->getMessage();       // Output: Call to undefined function call_undfined_function()
  $res->getCode();          // Output: 0
  $res->getLine();          // Output: 8
  $res->getFile();          // Output: C:\xampp\htdocs\promise\index.php
  $res->getTraceAsString(); // Output: #1 C:\xampp\htdocs\promise\index.php(9): Modassir\Promise\Promise->__construct(Object(Closure))
})->finally(function() {
  // echo: strtoupper('Hello Worlds!');
});
```

**OR**

```php
use Modassir\Promise\Promise;

$promise = new Promise(function($resolve, $reject) {
  $resolve('success');
  $reject('An error accurred.');
});

$promise->then(function($res) {
  // echo : 'success'
})->catch(function() {

})->finally(function() {
  // echo: strtoupper('Hello Worlds!');
});

$promise = new Promise(function($resolve, $reject) {
  $resolve('success');
  $reject('An error accurred.');
  call_undfined_function();
});

$promise->then(function() {

})->catch(function($res) {
  $res->getMessage();       // Output: Call to undefined function call_undfined_function()
  $res->getCode();          // Output: 0
  $res->getLine();          // Output: 8
  $res->getFile();          // Output: C:\xampp\htdocs\promise\index.php
  $res->getTraceAsString(); // Output: #1 C:\xampp\htdocs\promise\index.php(9): Modassir\Promise\Promise->__construct(Object(Closure))
})->finally(function() {
  // echo: strtoupper('Hello Worlds!');
});
```

## Resources
- [Report issue](https://github.com/lazervel/promise/issues) and [send Pull Request](https://github.com/lazervel/promise/pulls) in the [main Lazervel repository](https://github.com/lazervel/promise)
