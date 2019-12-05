#!/bin/bash

for folder in `ls -d */`; do
	echo ${folder%/} 
	docker build ./$folder -t "${folder%/}" 
done
