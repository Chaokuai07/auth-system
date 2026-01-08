FROM php:8.2-apache

# ติดตั้ง mysqli
RUN docker-php-ext-install mysqli

# เปิด mod rewrite (เผื่อใช้)
RUN a2enmod rewrite

# คัดลอกไฟล์ทั้งหมดเข้า apache
COPY . /var/www/html/

# สิทธิ์
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
