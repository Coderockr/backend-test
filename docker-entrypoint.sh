#!/bin/bash

python -m manage migrate
python -m debugpy --listen 0.0.0.0:5678 -m manage runserver 0.0.0.0:8000