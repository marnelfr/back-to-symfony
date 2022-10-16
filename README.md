# Security
Installation: ``composer req security``

In Symfony 5.3 the symfony's old and new security systems live
side-by-side and we can choose which one we want to use!
When ``enable_authenticator_manager`` is set to ``true``, it's activate
the new system.
In Symfony 6, we only have the new security system.

Talking about security, there are two big parts:
- **Authentication**: that ask "who are you"? And "can you prove it?".
  It's then related to Users, login forms, remember me cookies, passwords, API keys...
- **Authorization**: asking "Should you have access to this resource?".
  It's all about allowing or denying access to different things,
  like different URLs or controllers.

## The User
No matter what security system we're using, we need what will be 
authenticated: our user class.
The default provider used is ``users_in_memory`` when we add our 
security system.\
``make:user`` can help us to add 
- our user entity class,
- our user repository
- and our user provider and update our main security firewalls to use it.\
The user provider is an object that knows how to load our user objects... 
whether we're loading them from an API or from a database.

The user class implement the ``Symfony\Component\Security\Core\User\UserInterface``
which has mainly three methods:
This interface really has just 3 methods: 
- ``getUserIdentifier()``: that returns a visual identifier representing the user, 
- ``getRoles()``: deals with permissions 
- ``eraseCredentials()``: used to clear temporary and sensitive data stored on the user.











