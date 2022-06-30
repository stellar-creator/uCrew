FROM ubuntu:20.04

LABEL Description="uCrew KiCad 6 Docker Container"

RUN echo 'debconf debconf/frontend select Noninteractive' | debconf-set-selections && \
	apt-get clean autoclean && \
	apt-get -y update && \
	apt-get -y install gnupg2 && \
	apt-get -y install software-properties-common && \
	add-apt-repository --yes ppa:kicad/kicad-6.0-releases && \
	apt-get -y update && apt-get -y install kicad wget xclip xdotool xvfb python3-pip python3-colorama python3-xlsxwriter python3-yaml && \
	apt-get -y install python3-qrcodegen && \
	pip3 install lxml && \
	wget https://github.com/INTI-CMNB/KiBot/releases/download/v1.2.0/kibot_1.2.0-1_all.deb && \
	dpkg -i ./kibot_1.2.0-1_all.deb && \
	pip3 install xvfbwrapper && \
	pip3 install psutil && \
	pip3 install kiauto && \
	pip3 install robotframework-xvfb && \
	pip3 install Wand && \
	apt-get -y install libmagickwand-dev && \
	apt-get -y install imagemagick && \
	pip3 install PcbDraw && \
	apt-get -y install git && \
	mkdir -p ~/.kicad/scripting/plugins && \
	mkdir -p ~/.kicad_plugins && \
	cd ~/.kicad/scripting/plugins && \
	git clone https://github.com/INTI-CMNB/InteractiveHtmlBom.git && \
	cd ~/.kicad_plugins && \
	git clone https://github.com/INTI-CMNB/InteractiveHtmlBom.git && \
	apt-get -y install recordmydesktop

ADD user.json /root/.config/kicad/6.0/colors/user.json

WORKDIR /etc/ucrew-tmp/pcb/

CMD echo 'Run uCrew KiCad Docker Container' && kibot -v -c .kibot.yaml && echo 'Exit from uCrew KiCad Docker Container' && exit
