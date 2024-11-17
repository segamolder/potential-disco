- Run migrations
```sh
sail artisan migrate
```

- Run seeders
```sh
sail artisan db:seed --class=ArticleSeed && sail artisan db:seed --class=ArticleTagSeed
```