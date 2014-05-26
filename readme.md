
Tapatalk Client API
===================

This library provides the client API for Tapatalk written in PHP.

Install
-------

To install the libary use compser:

`composer.phar require netzmacht/tapatalk-client-api `


Usage
--------

The library does not use the exact method names as tapatalk does. You can see which methods are invoked by the `@see`
annotation in the source code. Its basically group the methods by the entities which are return or affected.


```php
<?php

// you don't have to use user credentials. if not passed not all features are available
$client = Netzmacht\Tapatalk\Factory::connect('http://example.com/forum/mobiquo/mobiquo.php', 'user', 'password');

// check if private messages are available
$client->config()->isPushTypeEnabled(Netzmacht\Tapatalk\Api\Config::PUSH_PRIVATE_MESSAGE);

$searchResult = $client->posts()->advancedSearch(array(
   Netzmacht\Tapatalk\Api\Search\AdvancedSearch::KEYWORDS => 'my keywords',
   Netzmacht\Tapatalk\Api\Search\AdvancedSearch::USERNAME => 'myuser',
   Netzmacht\Tapatalk\Api\Search\AdvancedSearch::ONLY_IN  => array(12, 43, 10), // forum ids
   20, // limit
   60,  // offset
));

$searchResult->getTotal(); // total posts. useful to
$searchResult->hasMore(); // returns true if given result is not as big as total posts

foerach($searchResult as $post) {
    $post->getAuthor()->getUsername();
}
```