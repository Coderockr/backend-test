package main

import (
	"net/http"
)

func main() {
	// http.Handle("/api/investors/", http.StripPrefix("/api/investors/", TODO))
	http.ListenAndServe(":8080", nil)
}