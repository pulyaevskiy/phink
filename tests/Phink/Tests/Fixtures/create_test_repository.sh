#!/bin/sh

git init
touch .gitignore
git add .
git commit -m "Initial commit"
touch composer.json
touch bootstrap.php
mkdir bin
touch bin/compile.sh
chmod +x bin/compile.sh
git add .
git commit -m "Added common files"

git tag v0.0.1

mkdir src
mkdir src/Test
touch src/Test/Model.php
git add .
git commit -m "Added test model"

git tag v0.0.2

git branch staging
git branch testing
