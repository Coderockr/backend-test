from rest_framework import status
from rest_framework.exceptions import APIException


class InvalidQueryParam(APIException):
    status_code = status.HTTP_400_BAD_REQUEST
    default_detail = "Invalid query params"
    default_code = "bad_request"
