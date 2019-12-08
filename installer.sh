#!/bin/bash



if [ "$1" = "web" ] || [ "$2" = "web" ]
then

	echo -e "\033[33m>>>Arrêt du conteneur 'lide-web' ... \033[0m" &&
	docker stop lide-web 2>/dev/null;

	echo -e "\033[33m>>>Création de l'image 'lide-web' (serveur web) ... \033[0m" &&
	docker build ./lide-web -t lide-web &&
	echo -e "\033[33m>>>Lancement d'un conteneur 'lide-web'... \033[0m" &&
	docker run --name lide-web --rm -d -p 9000:9000/tcp lide-web:latest  ||

	echo -e "\033[31m>>>ERREUR lors de la création de l'image lide-web !\033[0m" 
fi


if [ "$1" = "pma" ] || [ "$2" = "pma" ]
then

	echo -e "\033[33m>>>Arrêt du conteneur 'lide-pma' ... \033[0m" &&
	docker stop lide-pma 2>/dev/null;

	echo -e "\033[33m>>>Création de l'image 'lide-pma' (serveur web) ... \033[0m" &&
	docker build ./lide-pma -t lide-pma &&
	echo -e "\033[33m>>>Lancement d'un conteneur 'lide-pma'... \033[0m" &&
	docker run --name lide-pma --rm -d -p 7000:7000/tcp -p 7001:7001/tcp -v /var/run/docker.sock:/var/run/docker.sock  -v /lide_storage:/lide_storage lide-pma:latest ||

 	echo -e "\033[31m \nERREUR lors de la création de l'image lide-web !\033[0m" 
fi

##localhost:9000







