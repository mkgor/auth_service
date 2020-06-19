# Базовый образ с nginx и php
FROM richarvey/nginx-php-fpm

# Добавляем наше веб приложение
ADD . /var/www/app

# Удаляем конфиги сайтов которые там есть
RUN rm -Rf /etc/nginx/sites-enabled/*

# Добавляем наш конфиг
ADD docker/app/conf/nginx/service.conf /etc/nginx/sites-available/service.conf
# Включаем его
RUN ln -s /etc/nginx/sites-available/service.conf /etc/nginx/sites-enabled/service.conf