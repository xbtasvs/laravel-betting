# Bet2Gain

### Install Laravel dependencies
```
composer install
```

### Install node packages
```
npm install
```

### Compiles for development
```
npm run dev
```

### Compiles and hot-reloads for development
```
npm run hot
```

### Compiles and minifies files for production
```
npm run prod
```

### Customize configuration and work path
- Rename .env.sample to .env
- Edit APP_URL variable in .env :5
- Edit 'base' value in ./resources/js/app.js :22
- Edit axios baseURL value in ./resources/js/bootstrap.js :23
- Edit App\Providers\AuthServiceProvider domain and path :30
- Make sure this folder is in your apache default serve directory (e.g. www or htdocs)
- Edit DB connection settings in .env and create the DB in phpmyadmin
- Run php artisan migrate:fresh --seed (This will create all the tables and 2 sample users)

### Running the system
Simply visit the chosen work path in in your browser e.g. http::/localhost/Bet2Gain

### Sample users
- admin: admin@example.com admin
- user: user@example.com user
