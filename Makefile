.PHONY: deploy install

deploy:
    ssh o2switch 'cd ~/sites/toplissage.com && git pull origin main && make install'
    
install: .env vendor/autoload.php public/storage public/build/manifest.json
    php bin/console cache:clear
    php bin/console doctrine:migrations:migrate

.env:
    cp .env.example .env
    php bin/console generate:secret

public/storage:
    php bin/console assets:install public
    php bin/console asset:ic

vendor/autoload.php: composer.lock
    composer install
    touch vendor/autoload.php

public/build/manifest.json: package.json
    npm install
    npm run build