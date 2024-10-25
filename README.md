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

## Server
ssh ubuntu@18.141.181.19
ubuntu/abc@@123
docker ps
docker exec -it asset /bin/bash
docker logs container_name_or_id

figma : https://www.figma.com/design/ebzV5jXxGnI2a5B6SgDxmD/S-Office-(LongPV)?node-id=13121-33455
dbdiagram: https://dbdiagram.io/d/Quan-ly-tai-san-main-66c6e769a346f9518cc30b6d
