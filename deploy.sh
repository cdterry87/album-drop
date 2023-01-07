#!/bin/sh

set -e

vendor/bin/sail phpunit

(git push) || true

git checkout prod

git merge main

git push origin prod

git checkout main
