# Order Service
###### _Disclaimer: This is just an experimental project (WIP) containing some approaches and proof of concepts._

The idea behind the business domain is an application to order drinks and foods from a bar, restaurant or a cafe.


 
Order service is a microservice built using DDD, CQRS and Event Sourcing architecture, the goal is to provide an api to create, cancel, redo and deliver an order.

![Build](https://github.com/luciano-jr/order-service/workflows/Build/badge.svg)

[PHPUnit code coverage report](https://order-service-test-coverage.lucianojr.now.sh/Order/Domain/)


## Installation

Use [docker](https://docs.docker.com/install) to install the container services.

```bash
docker-compose up -d
```

This command will install an instance of Postgres, Nginx, RabbitMQ, Graphql and Metabase services.


## Usage

You can get the postman collection using the button:

[![Run in Postman](https://run.pstmn.io/button.svg)](https://app.getpostman.com/run-collection/63b68182803e7c638184#?env%5BOrder%20service%20%3A%3A%20Local%5D=W3sia2V5IjoiT1NFbmRwb2ludCIsInZhbHVlIjoiaHR0cDovLzEwLjI1NC4yNTQuMjU0IiwiZW5hYmxlZCI6dHJ1ZX0seyJrZXkiOiJPU1BvcnQiLCJ2YWx1ZSI6IjgwMjUiLCJlbmFibGVkIjp0cnVlfSx7ImtleSI6Ik9TR3JhcGhRTFBvcnQiLCJ2YWx1ZSI6IjU0MzMiLCJlbmFibGVkIjp0cnVlfV0=)

There are few endpoints in the collection: 

`/order-request` To request a new order

`/order-cancel` To request a cancel an existent order

`/order-deliver` To mark an order as delivered

`/order-redo` To re-order an order

`/graphiql` To query states using graphql

Once you request a command it will be sent to RabbitMQ to be handle by the consumer, so we need to run the consume:

`docker-compose exec php bin/console messenger:consume write_commands`

If the command be valid and produce an update against the aggregate (Order), it will dispatch an Event. The events also have their queue on the broker to be handle asynchronous.

To consume it simple run the follow:

`docker-compose exec php bin/console messenger:consume domain_events`

In order to query the orders we need to run the `order_projection` using the follow command: 

`docker-compose exec php bin/console event-store:projection:run order_projection`

It will project the orders into the read_order table, that can be read by using the graphql endpoint:

```
//POST http://10.254.254.254:5433/graphql
{
  allReadOrders {
    edges {
      node {
        orderId
        catalogFlowId
        catalogFlowVersion
        establishmentId
        tableIdentifier
        items
        status
      }
    }
  }
}
```

We can use [Metabase](https://www.metabase.com/) to have charts and results about the orders 

## Goals

Order Service
- [] Send notifications when order status change
- [] Create fixtures

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

`sh install-git-hooks.sh` to install git hooks.

## License
[MIT](https://choosealicense.com/licenses/mit/)
