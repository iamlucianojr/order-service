#!/bin/bash

# Localize the files changed and getting only the relative filename
FILES=$( git status --porcelain | grep -e '^[AC]\(.*\).php$' | cut -c 8-)

# If there are no PHP files, we don't need to continue into the script
if [[ ${FILES} == "" ]]; then
    echo "No files"
    exit 0;
fi

# Executing PHP Unit testing. If the tests fail, we exit the script earlier
echo "Executing PHP Unit testing. If the tests fail, we exit the script earlier"
docker-compose exec -T php /bin/bash -c "./vendor/symfony/phpunit-bridge/bin/simple-phpunit -c phpunit.xml.dist"
RESULT=$?

if ! [[ ${RESULT} -eq 0 ]]; then
    echo "###################################################################################################";
    echo "It was not possible to push your code!";
    echo "You tests did not pass, please fix it!";
    echo "###################################################################################################";
    exit 1
fi
