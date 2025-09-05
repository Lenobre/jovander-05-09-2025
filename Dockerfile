FROM ubuntu:22.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install -y apache2 mysql-server php libapache2-mod-php php-mysql && \
    apt-get clean

COPY start.sh /start.sh
RUN chmod +x /start.sh

RUN rm -f /var/www/html/index.html
COPY app/ /var/www/html/
COPY init.sql /root/
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80

ENTRYPOINT ["/start.sh"]
