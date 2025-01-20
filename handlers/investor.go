package handlers

import (
	"net/http"
)

type InvestorHandler struct {
}

func (i InvestorHandler) ServeHTTP(resp http.ResponseWriter, req *http.Request) {
	switch (req.Method) {
	case http.MethodPost:

	}
}
