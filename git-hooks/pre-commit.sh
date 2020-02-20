#!/bin/bash

SUCCESS=1

# Localize the files changed and getting only the relative filename
FILES=$( git status --porcelain | grep -e '^[ACMR]\(.*\).php$' | cut -c 8-)

# If there are no PHP files, we don't need to continue into the script
if [[ ${FILES} == "" ]]; then
    echo "No files"
    exit 0;
fi

# Check against the PHP Stan (Analysis tool trying to find bugs, or possible bugs)
echo "Check against the PHP Stan (Analysis tool trying to find bugs, or possible bugs)"
docker-compose exec -T php php -d memory_limit=2G ./vendor/phpstan/phpstan/bin/phpstan analyse ${FILES} -c phpstan.neon.dist
RESULT=$?

if [[ ${RESULT} -ne 0 ]]; then
    echo "###################################################################################################";
    echo "It was not possible to commit your code!";
    echo "Please check the messages above generated from PHPStan tool to understand how to fix the issues.";
    echo "###################################################################################################";
    exit 1
fi

# Check the coding standards
echo "Check the coding standards"
docker-compose exec -T php ./vendor/friendsofphp/php-cs-fixer/php-cs-fixer fix ${FILES} --config=.php_cs.dist -v --dry-run --show-progress=dots --using-cache=no --diff
RESULT=$?

if ! [[ ${RESULT} -eq 0 ]]; then
    echo "###################################################################################################";
    echo "It was not possible to commit your code!";
    echo "You must to fix the code style of your code!";
    echo "###################################################################################################";
    exit 2
fi

# PHP MD doesn't identify files that uses space as separator.
# So we need to replace the spaces to comma before executing the command
FILES_PHP_MD=$(echo ${FILES} | sed 's/ /,/g')

## Mess detector. It checks the code against good practices of coding
echo "Mess detector. It checks the code against good practices of coding"
docker-compose exec -T php /bin/bash -c "./vendor/phpmd/phpmd/src/bin/phpmd ${FILES_PHP_MD} xml phpmd.xml"
RESULT=$?

if ! [[ ${RESULT} -eq 0 ]]; then
    echo "###################################################################################################";
    echo "It was not possible to commit your code!";
    echo "You must clean your mess :D !";
    echo "###################################################################################################";
    exit 3
fi
