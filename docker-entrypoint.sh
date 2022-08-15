#!/bin/bash

python -m manage migrate
python -m manage runserver 0.0.0.0:8000