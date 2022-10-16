# Security
Installation: ``composer req security``

Since Symfony 5.3 the symfony's old and new security systems live
side-by-side and we can choose which one we want to use!
When ``enable_authenticator_manager`` is set to ``true``, it's activate
the new system.

Talking about security, there are two big parts:
- **Authentication**: that ask "who are you"? And "can you prove it?".
  It's then related to Users, login forms, remember me cookies, passwords, API keys...
- **Authorization**: asking "Should you have access to this resource?".
  It's all about allowing or denying access to different things,
  like different URLs or controllers.










