# Additional Containers for Local Development

## Traefik
```yml
version: '3.8'

# https://doc.traefik.io/traefik/user-guides/docker-compose/basic-example/
services:
  traefik:
    image: traefik:v3.1
    command:
      - --log.level=INFO
      - --api.insecure=true
      - --providers.docker=true
      - --providers.docker.network=traefik_network
      - --providers.docker.exposedByDefault=false
      - --entrypoints.web.address=:80
    ports:
      - "80:80"
      - "8080:8080" # Traefik Dashboard http://localhost:8080/
    volumes:
      - "/var/run/docker.sock:/var/run/docker.sock"
    networks:
      - proxy

networks:
  proxy:
    name: traefik_network

```

## Shared Database (or configure with Sail)
```yml
services:
  mariadb:
    image: mariadb:11
    container_name: mariadb
    restart: always
    environment:
      MYSQL_ROOT_PASSWORD: 'root'
      MYSQL_ROOT_HOST: "%"
      MYSQL_DATABASE: 'docker_mariadb'
      MYSQL_USER: 'docker_mariadb'
      MYSQL_PASSWORD: 'password'
      MYSQL_ALLOW_EMPTY_PASSWORD: 'yes'
    volumes:
      - mariadb_data:/var/lib/mysql
      - ./init.sql:/docker-entrypoint-initdb.d/init.sql
    ports:
      - "3307:3306"
    networks:
      - database

volumes:
  mariadb_data:
    driver: local

networks:
  database:
    name: mariadb_network

```
```sql
# init.sql
GRANT ALL PRIVILEGES ON *.* TO 'docker_mariadb'@'%';
FLUSH PRIVILEGES;
```