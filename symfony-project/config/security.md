# config/packages/security.yaml
security:
    # https://symfony.com/doc/current/security.html#registering-the-user-hashing-passwords
    password_hashers:
        Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface: "auto"
    # https://symfony.com/doc/current/security.html#where-do-users-come-from-user-providers
    providers:
        app_user_provider:
            entity:
                class: App\Entity\User
                property: email
    firewalls:
        dev:
            pattern: ^/(_(profiler|wdt)|css|images|js)/
            security: false
        main:
            lazy: true
            provider: users_in_memory
        developer:
            pattern: ^/auth-dev
            provider: app_user_provider
            custom_authenticator: App\Security\DeveloperAuthenticator
            logout:
                path: app_logout
                target: app_developer_login
        company:
            pattern: ^/company-login
            provider: app_user_provider
            custom_authenticator: App\Security\CompanyAuthenticateAuthenticator
            logout:
                path: app_logout
                target: app_company_login

            # activate different ways to authenticate
            # https://symfony.com/doc/current/security.html#firewalls-authentication

            # https://symfony.com/doc/current/security/impersonating_user.html
            # switch_user: true

    # Easy way to control access for large sections of your site
    # Note: Only the *first* access control that matches will be used
    access_control:
        - { path: ^/auth-dev, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/developer, roles: ROLE_DEV }
        - { path: ^/company-login, roles: IS_AUTHENTICATED_ANONYMOUSLY }
        - { path: ^/company, roles: ROLE_COMPANY }
