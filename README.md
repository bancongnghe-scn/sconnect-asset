## Setting
- npm i
- composer install
- php artisan migrate:fresh --seed
- touch .git/hooks/pre-commit
- vim .git/hooks/pre-commit
- Thêm đoạn mã sau vào file  (#!/bin/sh ./vendor/bin/php-cs-fixer fix git add .
  )
## Using
- Check quyền Auth::user()->can('permission_name')

