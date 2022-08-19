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
````php
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

## Dependency injection
````php
public function __construct(
    private HttpClientInterface $client, 
    private CacheInterface $cache
) {}
````
Starting with Symfony 6.1, we can set our non-autowireable 
arguments this way:
````php
public function __construct(
    private HttpClientInterface $client, 
    private CacheInterface $cache,
    #[\Symfony\Component\DependencyInjection\Attribute\Autowire('%kernel.debug%')]
    private bool $isDebug
) {}
````


## Parameters
The container doesn't only contain services. It also contains 
parameters that can be display using 
``debug:container --parameters``.\
We can add custom parameters thanks to the ``parameters`` key
inside the ``services.yaml`` config file. Parameters (``kernel.project_dir`` in instance) 
can be used in our config file with the syntax 
``'%kernel.project_dir%'``.

The ``services.yaml`` config file can also be used to set custom
services definitions when explicit configuration is needed:
````yaml
services:
    App\Service\MixRepository:
        bind:
            $isDebug: '%kernel.debug%'
            'bool $isDebug': '%kernel.debug%'
````
However, instead of completely overwriting the MixRepository
service, we could ``bind`` the ``$isDebug`` variable to every 
service in the ``_default`` section.

## Services.yaml
Thanks to configurations under the key ``services``, our classes
in ``src/`` are available to be used as services. They are then 
**Service classes**. Those created services' id is the 
fully-qualified class name, they are then **autowireable**.\
However, this except **Modal classes** or **DTOs** whose job is
mostly to hold data.

Thanks to the ``_defaults`` section, any of our custom services will
automatically have ``autowire: true`` and ``autoconfigure: true``.
So the container will automatically inject their dependencies and
configure them as commands, event subscribers, etc.

While it's possible to add our ``parameters`` in any files, we 
better let them in the ``services.yaml`` file.

**Autowiring, service auto-registration and other related 
features have zero performance effect when our app 
run in the prod env**

## Named autowiring
We can pre-configure some of our services. This doesn't overwrite 
them but add a named autowireable service.\
This is actually possible with the ``HttpClient`` service where we
can create a [scoping client](https://symfony.com/doc/current/http_client.html#scoping-client)
having a particular behavior like using a ``base-uri`` for example.

**Named autowireable** services have a specific name in the 
``debug:autowiring`` list. To use them and benefit everything 
their bring, they must be injected with their name. 


## Non-autowireable services
Those services can't be injected since they are not autowireable.
However, if we do really need them, we can ``bind`` them to our service
````yaml
services:
    App\Service\MixRepository:
        bind:
            $twigDebugCommand: '@twig.command.debug'
````
Or we can use the ``Autowrie`` attribute setting the named argument
``service``
````php
public function __construct(
    private CacheInterface $cache,
    #[Autowire(service: 'twig.command.debug')]
    private DebugCommand $twigDebugCommand
) {}
````
Let's execute a command manually in our PHP code:
````php
$output = new BufferedOutput();
$this->debugCommand->run(new ArrayInput([]), $output);
dd($output);
````

## Environment variables
They can be used to keep secret our token and other config 
variables. They are set in the ``.env`` file that Symfony reads
when it boots up to turn all of them into environment variables.\
[Been trying this and it doesn't work ->] However, if we've got a real env variable in our system with the
same name, that real env variable would win over the one in the 
``.env`` file.

They can be accessed from config files using: ``'%env(VAR_NAME)%'``\
Thanks to **processor system**, we can use ``'%env(trim:VAR_NAME)%'``
to trim white space on our env variables or ``'%env(file:VAR_FILE_PATH)%'``
when providing the path to a file that hold our env variable value.

``debug:dotenv`` command can be used to show our env variables 
and their values.


## Symfony's secrets vault
It's a set of files that contain environment variables in an 
encrypted form. They are then safe to be committed compared to 
our ``.env.local`` file.\
Commands:
- ``secrets:set VAR_NAME [--env=prod] [--local]``: to add a secret
- ``secrets:remove VAR_NAME``
- ``secrets:list [--reveal]``
- ...

When using secrets vault, we'll need two of them: one for the **dev**
and another for the **prod** environment.\
The secrets vault has for each environment, 
- a ``list.php`` file that stores a list of which 
values live inside the vault, 
- a ``encrypt.public.php`` file containing a cryptographic key that's
is used to add more secrets,
- a set of files that contains our secrets' encrypted values,
- a ``decrypt.private.php`` file containing the secret key to decrypt 
  and read the values in the vault. **This last file of the prod vault shouldn't be committed.** 

**Secrets** act like our env vars but if set, env vars values win.\
So dev secrets should contain our real key that should be set locally
using ``secrets:set VAR_NAME --local``


## Maker bundle
Installation: ``composer req maker --dev``
Thanks to it, we can create a command using 
``make:command app:talk-to-me`` for example where **app:talk-to-me**
is the name of our command.


## Doctrine
- ``doctrine:query:sql "select * from questions"`` used to 
make request to the database base.
- ``doctrine:migration:list`` displays the list of migrations 
and their status
- ``throw $this->createNotFoundException($message)`` can be 
used to trigger a 404 page. The ``$message`` it receive it's
only seen by developer.
- 






