FROM nginx:1.14

COPY content /usr/share/nginx/html
COPY conf.d /etc/nginx/conf.d

RUN apt-get update
RUN apt-get upgrade -y
RUN apt-get install iptables-persistent -y

RUN invoke-rc.d netfilter-persistent save
RUN service netfilter-persistent start

COPY iptables-rules.sh /
RUN chmod 0740 /iptables-rules.sh