package main

import (
	"database/sql"
	"encoding/json"
	"errors"
	"log"
	"net/http"

	"causeurgnocchi/backend-test/models"

	_ "github.com/go-sql-driver/mysql"
)

func main() {
	db, err := sql.Open("mysql", "root:example@(127.0.0.1:3306)/investments")
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	env := &Env{investors: &models.InvestorModel{DB: db}}
	
	http.HandleFunc("/api/investors?", env.investorsIndex)
	http.ListenAndServe(":8080", nil)
}

type Env struct {
	investors interface {
		Create(invstr models.Investor) error
		ByCPF(cpf string) (*models.Investor, error)
	}
}

func (env Env) investorsIndex(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodPost:
		var invstr models.Investor

		err := decodeJsonBody(w, r, &invstr)
		if err != nil {
			var mr *malformedRequest

			if errors.As(err, &mr) {
				http.Error(w, mr.msg, mr.status)
			} else {
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}
			return
		}

		err = env.investors.Create(invstr)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			return
		}

		invstrJson, err := json.Marshal(invstr)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			return
		}

		w.Header().Set("Content-Type", "application/json")
		w.Write(invstrJson)

	case http.MethodGet:
		cpf := r.URL.Query().Get("cpf")

		invstrs, err := env.investors.ByCPF(cpf)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			return
		}

		invstrsJson, err := json.Marshal(invstrs)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			return
		}

		w.Header().Set("Content-Type", "application/json")
		w.Write(invstrsJson)

	default:
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}
}