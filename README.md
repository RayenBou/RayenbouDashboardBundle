# RayenbouDashboardBundle

This project provides a Symfony integration for a ticketing system, allowing for the fetching of individual tickets and lists of tickets from a specified API. It utilizes the Symfony APIplateform  to expose an API and handles authentication through JWT token.

This project exist to be use with [Ticket Bundle](https://github.com/RayenBou/RayenbouTicketBundle).

The purpose of this bundle is to provide an easy way to integrate ticketing system in any app.

The DashboardBundle part can be installed on your app, while the TicketBundle part can be installed on any other app.

This documentation provides a step-by-step guide to setting up the dashboard environment for your project.

Right now the project is in Alpha and currently don't have any recipe but it might change soon.

## How to use it

Go to  `/dashboard/ticket/`.

And then you can :

1. Create an user with an auto-generated token as password.
2. Give this token to the app hosting the [Ticket Bundle](https://github.com/RayenBou/RayenbouTicketBundle).
3. Receive ticket, answer through a messenger-like conversation.
4. Close/open ticket as you wish.



## Initial Setup
1. **Composer**


   ```bash
   composer require rayenbou/dashboard-bundle
    ```

2. **Security Setup**

   Start by setting up the security component:
   ```bash
   php bin/console rayenbou:security
    ```
3. **Routing Configuration**

    Next, configure the routing for the dashboard by creating or updating the config/routes/rayenbou_dashboard.yaml file with the following content:

    ```yaml
    #config/routes/rayenbou_dashboard.yaml
    rayenbou_dashboard:
        resource: "@RayenbouDashboardBundle/Resources/config/routing.yaml"
    ```

4. **JWT Configuration**

    For JWT authentication, you need to generate a private and a public key. First, create a directory for JWT:

    ```bash
    mkdir -p config/jwt
    ```

    Then, generate the private key:

    ```bash
    openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    ```

    And generate the public key from the private key:

    ```bash
    openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```

    Remember to replace the passphrase with your clear text passphrase in the configuration.

5. **Database Setup**

    If your project does not have a database configured yet, you can create it and update the schema with the following commands:

    ```bash
    php bin/console d:d:c
    php bin/console doctrine:schema:update --force
    ```
## Tests

Unit tests and Integration tests are on their way.

## Evolution

1. Login, Post and Patch throttling to add security.
2. Possibility to override all templates and Controller.


## Contributing
Contributions to this project are welcome. Please ensure to follow the existing coding style and add unit tests for any new or changed functionality.

Please use `PHPstan` and `PHP-CS-FIXER`.

## License
This project is licensed under the MIT License - see the LICENSE file for details.


