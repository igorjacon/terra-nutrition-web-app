# Terra Nutrition Web App
This guide is intended to provide guidelines on how to setup and deploy the terra web app on a new server.

## Server Setup

### Requirements:
<ul>
    <li>Apache2.4</li>
    <li>Php 8.0+</li>
    <li>MySql 5.7+</li>
    <li>NodeJs 8+</li>
    <li>NPM 3.5+</li>
    <li>Composer</li>
</ul>

## Development Workflow

### Clone project repository
#### HTTPS
```
git clone https://github.com/igorjacon/terra-nutrition-web-app.git
```
#### Or
#### SSH
```
git clone git@github.com:igorjacon/terra-nutrition-web-app.git 
```
For ssh connections, an ssh-key is required

#### Install project files and dependencies
```
cd terra-nutrition-web-app
composer install
```

#### Create File Upload Directories and translation directory
```
mkdir public/uploads
mkdir public/media
mkdir translations
```
Grant write permissions to the "upload" directory

#### Create database
```
php bin/console doctrine:database:create
```

#### Upload data fixtures
```
php bin/console doctrine:fixtures:load --append
```

#### Download tranlsations
```
php bin/console translation:download
```

#### API configuration
```
php bin/console lexik:jwt:generate-keypair
```

#### Install and deploy static assets
```
npm install
npm run build
```

#### if missing .env file, copy these contents
```markdown
###> symfony/framework-bundle ###
APP_ENV=dev
APP_SECRET=2438217bd3d50ab54f8c54836f5fb0ad
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
DATABASE_URL=mysql://root:password@127.0.0.1:3306/terra_nutri
###< doctrine/doctrine-bundle ###

###> php-translation/loco-adapter ###
LOCO_PROJECT_API_KEY=
###< php-translation/loco-adapter ###

###> symfony/mailer ###
# MAILER_DSN=null://null
MAILER_DSN=smtp://noreply%40tms.peterandclark.com:r2swGZg2v23khHBjjC2TF%40ZJ8C9z9Ne@smtp.office365.com:587
###< symfony/mailer ###

###> nelmio/cors-bundle ###
CORS_ALLOW_ORIGIN=^.*$
###< nelmio/cors-bundle ###

###> lexik/jwt-authentication-bundle ###
JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
JWT_PASSPHRASE=51853cfcc95434d73ceeffc9e3e79abb33ce935477b313e1baab00af4fb7d7e6
###< lexik/jwt-authentication-bundle ###
```

#### services.yaml
```markdown
parameters:
    maintenance: false
    locale: en
    locales: [en, fr, pt]
    companyName: 'Terra Nutri'
    domain: 'http://localhost/'
    supportEmail: igorjacon90@gmail.com
    email_sender: { noreply@tms.peterandclark.com: iTms }

services:
    # default configuration for services in *this* file
    _defaults:
        autowire: true      # Automatically injects dependencies in your services.
        autoconfigure: true # Automatically registers your services as commands, event subscribers, etc.
        public: false
        bind:
            '$emailSender': '%email_sender%'

    # makes classes in src/ available to be used as services
    # this creates a service per class whose id is the fully-qualified class name
    App\:
        resource: '../src/'
        exclude:
            - '../src/DependencyInjection/'
            - '../src/Entity/'
            - '../src/Migrations/'
            - '../src/Kernel.php'


    App\Controller\:
        resource: '../src/Controller'
        tags: [controller.service_arguments]
```

#### To run the app
```
symfony server:start
```
#### open a browser and go to http://localhost