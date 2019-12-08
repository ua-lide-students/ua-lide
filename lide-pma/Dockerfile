FROM ubuntu:18.04

ARG DEBIAN_FRONTEND=noninteractive

#DEV à suppr
COPY resolv.conf / 

## Téléchargement Curl & wget & gnugpg
#RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN
RUN	apt update &&\
	apt install curl wget gnupg -yq &&\
	apt clean

## Ajout du paquet Docker
#RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN
RUN	curl -fsSL https://download.docker.com/linux/ubuntu/gpg | apt-key add - &&\
	echo "deb [arch=amd64] https://download.docker.com/linux/ubuntu bionic stable" >> /etc/apt/sources.list

## Téléchargement des dépendances
#RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN
RUN	apt update &&\
	apt install git php7.2 php7.2-xml php7.2-mysql php-ssh2 php-curl php-mbstring mariadb-server unzip apt-transport-https ca-certificates software-properties-common docker-ce -yq  &&\
	apt clean

## Téléchargement de Composer
#RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN
RUN	php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
	php -r "if (hash_file('sha384', 'composer-setup.php') === 'a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;" &&\
	php composer-setup.php &&\
	php -r "unlink('composer-setup.php');"

## Téléchargement de Symfony
#RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN
RUN	wget https://get.symfony.com/cli/installer -O - | bash &&\
    mv /root/.symfony/bin/symfony /usr/local/bin/symfony


COPY lide-pma/ /lide-pma
COPY dockers-execution/ /docker-execution

WORKDIR /lide-pma


## Installation des dépendances projet
##RUN cat /resolv.conf > /etc/resolv.conf &&\ \n RUN \n RUN
RUN    php /composer.phar install -n --no-progress --no-suggest &&\
	php bin/console cache:clear --no-warmup


## Setup de la base de données
ENV MYSQL_PASSWORD=lidepma \
    MYSQL_USER=lidepma

RUN service mysql start &&\
	mysql -u root --execute="CREATE USER '$MYSQL_USER'@'%' IDENTIFIED BY '$MYSQL_PASSWORD'" &&\
    mysql -u root --execute="GRANT ALL PRIVILEGES ON *.* TO '$MYSQL_USER'@'%' WITH GRANT OPTION" &&\
    mysql -u root --execute="FLUSH PRIVILEGES"

RUN	service mysql start  &&\
    php bin/console doctrine:database:create &&\
	php bin/console doctrine:schema:create &&\
	php bin/console doctrine:schema:update --dump-sql --force

## Lancement des services en mode détaché
RUN echo "service mysql start ; php bin/console server:run *:7000 & php bin/console lide:start-server & "  > /services.sh  &&\
	echo "cd /docker-execution/ && ./build-images.sh ; sleep infinity" >> /services.sh  &&\
 	chmod u+x /docker-execution/build-images.sh &&\
 	chmod u+x /services.sh
	 

EXPOSE 7000
EXPOSE 7001

ENTRYPOINT ["/bin/bash","-c","/services.sh"]

