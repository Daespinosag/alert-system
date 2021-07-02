#!/bin/bash
cp -r /usr/src/cache/node_modules/ /usr/src/app/node_modules/
cp -r /usr/src/cache/package-lock.json /usr/src/app/
npm rebuild node-sass --force
npm run dev
