#!/bin/bash

echo "Executing PHP Unit testing. If the tests fail, we exit the script earlier"
docker-compose exec -T php sh -c "./bin/phpunit -c phpunit.xml.dist --testdox ./tests"
RESULT=$?

if ! [[ ${RESULT} -eq 0 ]]; then
    echo "###################################################################################################";
    echo "It was not possible to push your code!";
    echo "Your tests did not pass, please fix it!";
    echo "###################################################################################################";
    exit 1
fi
