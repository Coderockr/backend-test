FROM python:3.10

RUN apt-get update \
    && apt-get install --no-install-recommends -y \
    curl wait-for-it

WORKDIR /app

COPY poetry.lock pyproject.toml /app/
RUN pip3 install poetry
RUN poetry config virtualenvs.create false
RUN poetry config --list
RUN poetry install

COPY . .