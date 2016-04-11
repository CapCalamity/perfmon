# Perfmon

This is the main Perfmon repository.

This is a personal project to remotely monitor the system-resources of any given linux-based machine i have.

# Installation

This site is based on Laravel, so it needs to be installed.

For this, composer should be available on the system. If that is not the case please visit [this](https://getcomposer.org/) for more information on how to install composer.

The basic deployment requires only these few steps:

 - clone the repository


        git clone https://github.com/CapCalamity/perfmon

 - install all dependencies


        composer install
        npm install
        bower install

 - compile the resources with gulp / elixir


        gulp

 - copy the `.env.example` file to `.env` and change the values to match your configuration

 - run `php artisan key:generate` to generate a new key for your application

To function properly there needs to be at least one instance of the [perfmon-reporter](https://github.com/CapCalamity/perfmon-reporter) project running.

~To be filled~

## License

This Project is licensed under the [MIT License](http://opensource.org/licenses/MIT).

This project is based on the [Laravel](https://laravel.com/) PHP Framework.

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
