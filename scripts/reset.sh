#!/usr/bin/env bash
app/console doctrine:database:drop --force
app/console doctrine:database:create
app/console doctrine:schema:update --force
