FROM php:8.2-apache

# ติดตั้ง PDO MySQL
RUN docker-php-ext-install pdo pdo_mysql

# เปิด mod_rewrite (เผื่อ redirect)
RUN a2enmod rewrite

# copy โค้ดเข้า apache
COPY . /var/www/html/

# ตั้ง permission
RUN chown -R www-data:www-data /var/www/html
