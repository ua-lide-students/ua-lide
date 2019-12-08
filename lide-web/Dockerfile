FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

COPY resolv.conf / 

## Téléchargement des dépendances
#RUN cat /resolv.conf > /etc/resolv.conf &&\
RUN	apt update &&\
	apt install git php7.2 php7.2-xml php7.2-mysql php-ssh2 php-curl mariadb-server wget curl unzip -yq &&\
	apt clean

## Téléchargement de Composer
#RUN cat /resolv.conf > /etc/resolv.conf &&\
RUN	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
	php composer-setup.php &&\
	php -r "unlink('composer-setup.php');"

## Téléchargement de Symfony
#RUN cat /resolv.conf > /etc/resolv.conf &&\
RUN	curl -LsS https://symfony.com/installer -o /usr/local/bin/symfony

COPY lide-web/ /lide-web
WORKDIR /lide-web

## Installation des dépendances projet
#RUN cat /resolv.conf > /etc/resolv.conf &&\
RUN    php /composer.phar install -n --no-progress --no-suggest &&\
	php bin/console cache:clear --no-warmup
	
## Setup de la base de données
ENV MYSQL_PASSWORD=admin \
    MYSQL_USER=admin

RUN service mysql start &&\
	mysql -u root --execute="CREATE USER '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD'" &&\
    mysql -u root --execute="GRANT ALL PRIVILEGES ON *.* TO '$MYSQL_USER'@'%' WITH GRANT OPTION" &&\
    mysql -u root --execute="FLUSH PRIVILEGES"

RUN	service mysql start  &&\
	php bin/console doctrine:database:create &&\
	php bin/console doctrine:schema:create &&\
	php bin/console doctrine:schema:update --dump-sql --force &&\
	php bin/console doctrine:fixtures:load


## Lancement des services en mode détaché
RUN echo "service mysql start & php bin/console server:run *:9000 && sleep infinity" > /services.sh  &&\
 	chmod u+x /services.sh

EXPOSE 9000

ENTRYPOINT ["/bin/bash","-c","/services.sh"]

