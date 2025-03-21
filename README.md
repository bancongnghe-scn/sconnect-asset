## Setting
- npm i
- composer install
- php artisan migrate:fresh --seed
- touch .git/hooks/pre-commit
- chmod +x .git/hooks/pre-commit
- vim .git/hooks/pre-commit
- Thêm đoạn mã sau vào file  (#!/bin/sh ./vendor/bin/php-cs-fixer fix git add .)
  <!-- git commit -am "build" -->
## Using
- Check quyền Auth::user()->canPer('permission_name')
- Tạo controller, service, repository, model : php artisan make:crms {name}
## Server
- docker ps
- docker exec -it asset /bin/bash
- docker logs container_name_or_id
- mysql -u root -p
- SHOW DATABASES;
## Tài liệu
- figma : https://www.figma.com/design/ebzV5jXxGnI2a5B6SgDxmD/S-Office-(LongPV)?node-id=1-3&p=f&t=QIfqqswefmELfTb0-0
- dbdiagram: https://dbdiagram.io/d/Quan-ly-tai-san-main-66c6e769a346f9518cc30b6d

## Chạy lệnh pusher 
- php artisan websockets:serve --port=?
- php artisan queue:work

## Git
- git rm -r --cached .
