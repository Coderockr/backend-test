Third-Party Packages
####################

This project was builded using **Python 3.8**, **Django 3.1** and **DRF 3.12**.


Documentation
-------------

* drf-spectacular_ - Flexible and easy lib to generate api schema with OpenApi 3.0.
* sphinx_ - I did use it to build this page using .rst files and importing python code.

Test
----

* model_bakery_ - Easy way to create random (or not) model objects, how many you wish.
* coverage_ - I used it to know what I did need to test.

Code Style
----------

* flake8_ - Python lint code.
* black_ - Python code formatter.
* isort_ - To organize imports.
* pre-commit_ - I did use to auto run flake8, black and isort hooks before commit.

Authentication
--------------

* simplejwt_ - To generate and manage access tokens.
* djoser_ - To manage authentication and reuse user endpoints that djoser offers. Has compatibility with Custom User implementation.

Database
--------

* postgres_ - Used as a Docker Compose service.

Filter
------

* django-filter_ - Easy way to add filter to model fields or create custom filters.

.. _coverage: https://coverage.readthedocs.io/en/coverage-5.3.1/
.. _drf-spectacular: https://drf-spectacular.readthedocs.io/en/latest/
.. _sphinx: https://www.sphinx-doc.org/en/master/usage/quickstart.html
.. _model_bakery: https://model-bakery.readthedocs.io/en/latest/
.. _flake8: https://flake8.pycqa.org/en/latest/
.. _black: https://black.readthedocs.io/en/stable/
.. _isort: https://pycqa.github.io/isort/
.. _pre-commit: https://pre-commit.com/
.. _simplejwt: https://django-rest-framework-simplejwt.readthedocs.io/en/latest/
.. _djoser: https://djoser.readthedocs.io/en/latest/getting_started.html
.. _postgres: https://hub.docker.com/_/postgres
.. _django-filter: https://django-filter.readthedocs.io/en/stable/
