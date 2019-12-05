#!/bin/bash


echo -e "\033[33m>>>Arrêt du conteneur 'lide-web' ... \033[0m" &&
	docker stop lide-web 2>/dev/null;

echo -e "\033[33m>>>Lancement d'un conteneur 'lide-web'... \033[0m" &&
	docker run --name lide-web --rm -d -p 9000:9000/tcp lide-web:latest

echo -e "\033[33m>>>Arrêt du conteneur 'lide-pma' ... \033[0m" &&
	docker stop lide-pma 2>/dev/null;

echo -e "\033[33m>>>Lancement d'un conteneur 'lide-pma'... \033[0m" &&
	docker run --name lide-pma --rm -d -p 7000:7000/tcp -p 7001:7001/tcp -v /var/run/docker.sock:/var/run/docker.sock  -v /lide_storage:/lide_storage lide-pma:latest 
