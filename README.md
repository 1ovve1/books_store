# Books Store app

## Install

* clone repo:
```bash
git clone https://github.com/1ovve1/books_store 
```

* create db-local.php and configure db connection:
```bash
cp ./config/db.php ./config/db-local.php
nano ./config/db-local.php
```

* run migrations:
```bash 
./yii migrate
```

* start built in server:
```bash
./yii serve/index
```