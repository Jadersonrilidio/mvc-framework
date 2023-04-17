## MVC Framework in PHP

A complete and functional framework for building webpages and APIs.


## IMPORTANT NOTES!

 - This project is still under development, lacks some features as unitary and integrated tests and features as ORM and complete database abstraction layer, amongst other usefull implementations.

 - The main goal to build this project is for personal learning purposes, althought it is totally functional, security and bug proof are not guaranteed. That being said,  if a developer whishes to apply to a web project, use it at your own risk.

 - Suggestions, issues-reporting and improvements are all welcome, please open an issue thread or pull request if you find necessary.


## INSTALL / QUICK START

Download the project via composer packagist:

```bash
    $ composer create-project jayrods/mvc-framework project_directory_name
```

Run the command:

```bash
    $ composer install
```

to create the `composer.lock` file and add required dependencies.

Create and configure a `.env` environment variables file in order to run your application correctly:

```bash
    APP_URL=http://localhost
    ENVIRONMENT={development,production}
    MAINTENANCE=false
    EMAIL_ADDRESS=email@example.com
    EMAIL_PASSWORD=
    EMAIL_SMPT_HOST=smtp.example.com
    OPENSSL_SECRET=
    CACHE_EXPIRATION_TIME=30
    PASSWORD_ALGO=
```

NOTE: if an environment variable is not set, the application will use its default values.

Finally, run the command:

```bash
    $ composer serve
```

to start the application with its default php built-in server on port 8001. All the basic user authentication routes are set by default.

Then just acess the URL and check if the application is running successfully!


NOTE: The `composer serve` command is by default set as:

```json
    {
        "scripts": {
            "serve": [
                "Composer\\Config::disableProcessTimeout",
                "php -S localhost:8001 -t public/"
            ],
        }
    }
```

which is suited for development purposes, whonever the user is free to modify the composer script commands at will in the `composer.json` file.


## BASIC USE

Documentation writing in progress...
