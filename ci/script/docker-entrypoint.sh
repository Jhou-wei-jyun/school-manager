#!/bin/sh

NPM_COMMAND=$1
# watch-poll, development, production

cd /workspace
npm config set loglevel verbose
npm install --no-bin-links && npm run ${NPM_COMMAND}
