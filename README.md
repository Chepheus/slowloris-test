# Slowloris Test
Slowloris DDoS attack and protection

### Network
1. `docker network create --driver bridge custom_network --subnet 172.30.100.1/20`

### Server:
1. `docker build -t nginx-server server/`
2. `docker run -d -p 80:80 --cap-add=NET_ADMIN --network custom_network --ip 172.30.100.1 --name server nginx-server`
3. `docker exec -it server bash /iptables-rules.sh`

After this server is available on [http://172.30.100.1/](http://172.30.100.1/)

### Attack:
1. `docker build -t slowloris-attack slowloris/`
2. `docker run -it --network custom_network --rm --name slowloris slowloris-attack php slowloris.php 1500 172.30.100.1`