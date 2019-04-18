# slowloris-test
Slowloris DDoS attack and protection

### Server:
1. `docker build -t nginx-server server/`
2. `docker network create --driver bridge custom_network --subnet 172.30.100.1/20`
3. `docker run -d -p 8080:80 --network custom_network --ip 172.30.100.1 nginx-server`

After this server is available on [http://172.30.100.1/](http://172.30.100.1/)

### Attack:
1. `docker build -t slowloris-attack slowloris/`
2. `docker run -it --network custom_network --rm slowloris-attack php slowloris.php 1500 172.30.100.1`