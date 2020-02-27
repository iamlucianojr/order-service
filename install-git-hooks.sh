#!/bin/bash

cat ./git-hooks/pre-commit.sh > ./.git/hooks/pre-commit
chmod +x ./.git/hooks/pre-commit

cat ./git-hooks/pre-push.sh > ./.git/hooks/pre-push
chmod +x ./.git/hooks/pre-push

