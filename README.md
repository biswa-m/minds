Fansonly
=====

## Repositories

Minds is split into multiple repositories:

- [Engine]- Backend code & APIs
- [Front] - Client side Angular2 web app
- [Sockets] - WebSocket server for real-time communication


## Development System Requirements

- > 10GB RAM (be sure to set it in your docker settings)
- > 100GB Disk space
- [Docker Compose](https://docs.docker.com/compose/)

## Development Installation

**Enabling full installation**

### Setting up elasticsearch

** Linux users **
To get elasticsearch 6 to run, you must make a settings change on the host machine.

Run ```sudo sysctl -w vm.max_map_count=262144```

To make it permanent, modify the variable in /etc/sysctl.conf

#### Build the elasticsearch indexes

1. Make sure nothing is running: `docker-compose down`
2. Run the legacy provisioner: `docker-compose up elasticsearch-legacy-provisioner`
3. Run the legacy provisioner: `docker-compose up elasticsearch-provisioner`

### Running the stack

aws s3 bucket name is hard-coded, find and replace aws s3 bucket name if needed.

1. Run `sh init.sh` in order to install the front and engine repositories
2. Run `docker-compose up -d nginx`
3. Run `docker-compose up installer` (one time only.. initial username: minds / password: Pa$$w0rd)
4. Check engine/settings.php if somekeys are missing update manually from engine/settings.example.php. (search for TODO at settings.example.php) 
5. if settings.php is changed manually repeat step 2 and 3.
6. Run `docker-compose up sockets`
7. Run `docker-compose up runners`
8. Run `docker-compose exec php-fpm php /var/www/Minds/engine/cli.php QueueRunner run --runner=Transcode` Transcode queue runner manually. 
9. cd front and Run `docker-compose up front-build` 

# Troubleshooting

- Minds is already installed
  - Ensure engine/settings.php does not exist and re-run `docker-compose up installer`

- Cassandra will not boot
  - Ensure thrift is enabled
  - Cassandra requires at least 4GB of memory to operate. You can start Cassandra manually by running `docker-compose up cassandra`

### Nuclear Option

With dockerized enviroments, it's sometimes best to start from scratch. If you want to delete your data, these steps will completely **delete** your data. You will be starting fresh.

```
  #Remove your settings file
  rm engine/settings.php 
  
  #Stop your stack
  docker-compose down

  #Delete your data cache
  rm -rf .data

  #Purge all volumes
  docker volume prune

  ```

  That will remove all of your locally cached data. You can either rebuild the containers manually by using ```docker-compose up --build``` or delete everything to start fresh.

```
  # Delete all containers
  docker rm $(docker ps -a -q)

```

## Production System Requirements

At this time it is not advisable to run Minds in production, however it is possible so long as you are aware of the risks.

- 3 Cassandra Nodes (Min 30gb RAM, 1TB SSD, 8 CPU)
- 1 ElasticSearch Node (Min 16GB RAM, 250GB SSD, 8 CPU) #2 nodes are recommended for failover
- 1 Docker Machine (Min 60gb RAM, 50GB SSD, 32 CPU)


## Contributing
If you'd like to contribute to the Minds project, check out the [Contribution](https://www.minds.org/docs/contributing.html) section of Minds.org or head right over to the [Minds Open Source Community](https://www.minds.com/groups/profile/365903183068794880).  If you've found or fixed a bug, let us know in the [Minds Help and Support Group](https://www.minds.com/groups/profile/100000000000000681/activity)!

## Security reports
Please report all security issues to [security@minds.com](mailto:security@minds.com).

## License
[AGPLv3](https://www.minds.org/docs/license.html). Please see the license file of each repository.

## Credits
[PHP](https://php.net), [Cassandra](http://cassandra.apache.org/), [Angular2](http://angular.io), [Nginx](https://nginx.com), [Ubuntu](https://ubuntu.com), [OpenSSL](https://www.openssl.org/), [RabbitMQ](https://www.rabbitmq.com/), [Elasticsearch](https://www.elastic.co/), [Cordova](https://cordova.apache.org/), [Neo4j](https://neo4j.com/), [Elgg](http://elgg.org), [Node.js](https://nodejs.org/en/), [MongoDB](https://www.mongodb.com/), [Redis](http://redis.io/), [WebRTC](https://webrtc.org/), [Socket.io](http://socket.io/), [TinyMCE](https://www.tinymce.com/), [Ionic](http://ionicframework.com/), [Requirejs](http://requirejs.org/), [OAuth](http://oauth.net/2/), [Apigen](http://www.apigen.org/), [Braintree](https://www.braintreepayments.com/). If any are missing please feel free to add.

___Copyright Minds 2012 - 2018___

Copyright for portions of Minds are held by [Elgg](http://elgg.org), 2013 as part of the [Elgg](http://elgg.org) project. All other copyright for Minds is held by Minds, Inc.
