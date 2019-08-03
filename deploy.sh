#!/bin/sh

# update source code
git pull origin master --force
git -C ../bluebird-client pull --force

rm -rf resources/client
cp ../bluebird-client/src/ resources/client -r
yarn
yarn build

# update composer
