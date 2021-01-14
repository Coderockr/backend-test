FROM python:3.8
ENV PYTHONUNBUFFERED=1
WORKDIR /code

RUN pip install poetry && poetry config virtualenvs.create false

EXPOSE 8000 8000

COPY . /code/
