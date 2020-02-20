# Order Service

Order service is a microservice built using DDD, CQRS and Event Sourcing architecture

![PHP Composer](https://github.com/luciano-jr/order-service/workflows/PHP%20Composer/badge.svg?branch=master)
![Docker Image CI](https://github.com/luciano-jr/order-service/workflows/Docker%20Image%20CI/badge.svg?branch=master)


## Installation

Use [docker](https://docs.docker.com/install) to install the container services.

```bash
docker-compose up
```

This command will install an instance of Postgres, Nginx and RabbitMQ.


## Usage
You can get the postman collection using the button:

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/63b68182803e7c638184#?env%5BOrder%20service%20%3A%3A%20Local%5D=W3sia2V5IjoiT1NFbmRwb2ludCIsInZhbHVlIjoiaHR0cDovLzEwLjI1NC4yNTQuMjU0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJPU1BvcnQiLCJ2YWx1ZSI6IjgwMjUiLCJlbmFibGVkIjp0cnVlfV0=)

There are few endpoints: 

`/order-request` To request a new order

`/order-cancel` To request a cancel an existent order

`/order-deliver` To mark an order as delivered

`/order-redo` To re-order an order

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
