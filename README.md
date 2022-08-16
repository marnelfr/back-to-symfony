# Symfony fundamentals
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
Need to perform some requests, this library is there for you.



