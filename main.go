package main

import (
	"causeurgnocchi/backend-test/handlers"
	"net/http"
)

func main() {
	http.Handle("/api/investors/", http.StripPrefix("/api/investors/", &handlers.InvestorHandler{}))
	http.ListenAndServe(":8080", nil)
}