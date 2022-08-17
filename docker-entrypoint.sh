#!/bin/bash

wait-for-it db:5432 -- python -m manage migrate
wait-for-it redis:6379 -- python -m debugpy --listen 0.0.0.0:5678 -m manage runserver 0.0.0.0:8000