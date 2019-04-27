# lemp.docker.app

## PHP 

### Build Setup

``` bash
# install dependencies
docker run --rm --interactive --tty \
    --volume $PWD:/app \
    composer install
    
# run migrations from phpfpm container
php vendor/bin/phinx migrate

# run seeds from phpfpm container
php vendor/bin/phinx  seed:run



```

## Vue.js 

### Build Setup

``` bash
# install dependencies
npm install

# serve with hot reload at localhost:8080
npm run dev

# build for production with minification
npm run build
```

For detailed explanation on how things work, consult the [docs for vue-loader](http://vuejs.github.io/vue-loader).
