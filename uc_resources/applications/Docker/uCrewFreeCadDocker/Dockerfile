FROM ubuntu:20.04

LABEL Description="uCrew FreeCad 0.20 Docker Container"

ADD 3dconverter.py /usr/local/bin/3dconverter.py

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections && \
	apt-get clean autoclean && \
	apt-get -y update && \
	apt-get -y install gnupg2 && \
	apt-get -y install software-properties-common && \
	add-apt-repository --yes ppa:kicad/kicad-6.0-releases && \
	apt-get -y update && apt-get -y install freecad wget xclip xdotool xvfb python3-pip python3-colorama python3-xlsxwriter python3-yaml && \
	apt-get -y install python3-qrcodegen && \
	pip3 install lxml && \
	pip3 install xvfbwrapper && \
	pip3 install psutil && \
	pip3 install kiauto && \
	pip3 install robotframework-xvfb && \
	pip3 install Wand && \
	apt-get -y install libmagickwand-dev && \
	apt-get -y install imagemagick && \
	apt-get -y install recordmydesktop

WORKDIR /etc/ucrew-tmp/3d/

CMD echo 'Run uCrew FreeCad Docker Container' && \
	python3 /usr/local/bin/3dconverter.py /etc/ucrew-tmp/3d/file.step /etc/ucrew-tmp/3d/file.stl && \
	python3 /usr/local/bin/3dconverter.py /etc/ucrew-tmp/3d/file.step /etc/ucrew-tmp/3d/file.x3d && \
	echo 'Exit from uCrew FreeCad Docker Container' && \
	exit
