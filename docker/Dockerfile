FROM python:3.10

WORKDIR /usr/src/app

COPY .env /usr/src/app
COPY pyproject.toml /usr/src/app
RUN pip install poetry
RUN poetry install


COPY ./app /usr/src/app


CMD [ "poetry", "run", "python", "/usr/src/app/manage.py", "runserver", "0.0.0.0:8080"]