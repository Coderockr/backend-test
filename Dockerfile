# devguerreiro/python-drf
FROM python:3.8-slim-buster
LABEL maintainer="Luis Guerreiro <devcorujam@gmail.com>"
ENV PYTHONUNBUFFERED=1
WORKDIR /code

EXPOSE 8000 8000

RUN apt-get update \
    && apt-get install -y --no-install-recommends git \
    && apt-get purge -y --auto-remove \
    && rm -rf /var/lib/apt/lists/*

RUN pip install poetry==1.1.4 && poetry config virtualenvs.create false

#to install dependencies always when removed or added
COPY poetry.lock pyproject.toml /code/
RUN poetry install --no-root

COPY . /code/
RUN poetry install && pre-commit install