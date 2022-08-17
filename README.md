# Symfony 6
## Bundles
Bundle are Symfony plugins. They're normal PHP packages,
except that they plug in into Symfony. And the main 
thing they give are services.
They register some PHP classes they come with 
as services, so they can be used via **autowiring**.
Thanks to the Flex recipes attached to them, they are enable 
automatically in ``config/bundles.php``

## UX Turbo
Installation: ``composer req symfony/ux-turbo``\
Turns our app into an single page app. Every request is 
then make by ajax.

## Debug
- ``php bin/console debug:autowiring`` returns the list 
of every classes that can be autowired.
- ``php bin/console router:match /api/song/1 --method=get``
  check if there is any route that match the provided one.
- ``php bin/console debug:twig`` print the list of every 
functions, filters, tests and global variable in twig.

## KnpTimeBundle
Installation: ``composer require knplabs/knp-time-bundle``\
Give us the ``ago`` filter and the ``time_diff(date_time)``
function that returns friendly "2 hours ago"-type messages.

## Http Client
Installation: ``composer req symfony/http-client``\
Need to perform some requests, this library is there 
for you.\
Once the library is installed, the **FrameworkBundle
registers a service** called **HttpClientInterface** which uses 
a PHP class from ``symfony/http-client``.

However, making request may slow down our application.
To fix this, we can use the cache: ``CacheInterface`` 
from ``Symfony/Contracts``.
````injectablephp
$mixes = $cache->get('mixed.data.list', function(CacheItemInterface $cacheItem) use ($client) {
    $cacheItem->expiresAfter(5);
    $response = $client->request('GET', $url);
    return $response->toArray();
});
````
Here we're caching our request's response that will be deleted
after 5 seconds.

### Clear the cache
We have a bunch of different categories of cache called
**cache pools**. We can show them thanks to ``cache:pool:list``.

Since the cache we're using is ``cache.app``, we can clear it 
with ``cache:pool:clear cache.app``


## Bundle configuration
We can control how bundles configure its services through config 
files in the ``/config/packages`` directory.
Each of them config a service related to its root key.
- ``debug:config bundle_alias`` shows all the current configuration 
of the bundle including defaults one.
- ``config:dump twig`` shows us a giant tree of example configuration
which includes everything that's possible. 

## Service container
Contains the list of services in our app (or in the container).
It's basically a giant **array** where each service has a 
unique name that points to its service object. 
- ``debug:container`` shows us the full list of services in our app.

The container only instantiate a given service once during a request. 
The service maybe asked several time, it'll be instantiated once and 
the container will return the same one instance.\
However, every service in the container is not autowireable. Those
who actually are can be display by the command ``debug:autowiring``.

## Environments
We have three environments in symfony: prod, dev, and test.
At the entry point of our app, the Kernel is instantiated with our
environment and the debug mode.\
Base on that, our config files are loaded.
We can have 3 types of config for each environment.
The default config is loaded and then overwrite by the configuration 
of our environment.









