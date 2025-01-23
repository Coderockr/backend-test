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

	http.HandleFunc("/api/investors/{cpf}", env.investorsByCpf)
	http.HandleFunc("/api/investors", env.investorsCreate)
	http.ListenAndServe(":8080", nil)
}

type Env struct {
	investors interface {
		Create(invstr models.Investor) error
		ByCpf(cpf string) ([]models.Investor, error)
	}
}

func (env Env) investorsCreate(w http.ResponseWriter, r *http.Request) {
	var invstr models.Investor

	err := decodeJsonBody(w, r, invstr)
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
}

func (env Env) investorsByCpf(w http.ResponseWriter, r *http.Request) {
	cpf := r.PathValue("cpf")

	invstrs, err := env.investors.ByCpf(cpf)
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
}
