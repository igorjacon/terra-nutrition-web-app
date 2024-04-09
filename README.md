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
php bin/console doctrine:migrations:migrate
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

#### Extra Configurations
To enable container on Locale subscriber
```markdown
```

#### To run the app
```
symfony server:start
```
#### open a browser and go to http://localhost