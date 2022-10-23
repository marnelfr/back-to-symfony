# Security
Installation: ``composer req security``

In Symfony 5.3 the symfony's old and new security systems live
side-by-side and we can choose which one we want to use!
When ``enable_authenticator_manager`` is set to ``true``, it's activate
the new system.
In Symfony 6, we only have the new security system.

Talking about security, there are two big parts:
- **Authentication** [Who you are]: that ask "who are you"? And "can you prove it?".
  It's then related to Users, login forms, remember me cookies, passwords, API keys...
- **Authorization** [What you can do]: asking "Should you have access to this resource?".
  It's all about allowing or denying access to different things,
  like different URLs or controllers.

## The User
No matter what security system we're using, we need what will be 
authenticated: our user/company/client/broker class.

The user class implement the ``Symfony\Component\Security\Core\User\UserInterface``
which has mainly three methods:
- ``getUserIdentifier()``: that returns a visual identifier representing the user,
- ``getRoles()``: deals with permissions
- ``eraseCredentials()``: used to clear temporary and sensitive data stored on the user.

The default provider used by our security system is ``users_in_memory``.\
Using ``make:user``, we can add 
- our user entity class,
- our user repository
- and our user provider and update the main security firewalls to use it.\
The user provider is an object that knows how to load our user objects... 
whether we're loading them from an API or from a database.

Once with have our user class, we've the command ``make:auth`` that
can help us to generate everything we need to build a login form system. 

## Firewalls & Authenticators
A firewall is all about authentication.\
At the start of every request, before Symfony calls the controller, 
the security system executes a set of "authenticators". The job of each 
authenticator is to look at the request, see if there is any authentication 
information that it understands (like a submitted email and password, or 
an API key that's stored on a header) and if there is, use that to query 
the user and check the password. If all that happens successfully then, 
the authentication complete, so the request can proceed.\
All of this happens thanks to the ``security.firewalls`` configuration.\

At the start of each request, Symfony goes down the list of firewalls, 
reads the pattern key (which is a regular expression) and finds the first 
firewall whose pattern matches the current URL. 
**So there's only ever one firewall active per request.**\
And that's why the ``security.firewalls.dev`` come first to (actually)
disable the security system for some URLs.

However, our ``security.firewalls.main`` doesn't have a pattern. That's means
it'll match all requests that don't match the dev firewall.

``security.firewalls.main.lazy: true`` allows the authentication system
to not authenticate the user until it needs to.

The ``make:auth`` command, can be used create an **Empty authenticator**; it
updates our ``security.yaml`` file to use it hence the 
``security.firewalls.main.custom_authenticator``.

The only rule about an authenticator is that it needs to implement 
``AuthenticatorInterface`` though usually our authenticator extend 
``AbstractAuthenticator`` which implements ``AuthenticatorInterface``.

Once we activate our authenticator in the security system, 
at the beginning of every request, before reaching the controller, 
Symfony will then call its ``supports()`` method to check authentication
information within it.

It almost never makes sense to have two firewalls... even if you have two different
ways to authenticate. This should be moved to one firewall. 
The exception to that rule is if you have, for example, a frontend that has one 
User class and an API under ``/api/`` where, if you log in, you will be logged in 
as a completely different user class - e.g. ``ApiUser``.





