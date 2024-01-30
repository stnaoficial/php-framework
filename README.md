# Getting Started
You can access the global kernel instance at any time through the `kernel()` function.

## Boostraper
Bootstrap the kernel framework calling the `boot()` method provided by the `\Miscellaneous\Kernel` global instance:

```php
kernel()->boot();
```

## Service Providers
Register an service provider simply passing it as the first parameter of the `register()` method provided by the global kernel instance.

```php
kernel()->register(MyServiceProvider::class);
```

You can also unregister an service provider calling the `unregister()` method.

```php
kernel()->unregister(MyServiceProvider::class);
```