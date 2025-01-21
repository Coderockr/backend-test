package main

import (
	"database/sql"
	"encoding/json"
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
	http.ListenAndServe(":8080", nil)
}

type Env struct {
	investors interface {
		ByCpf(cpf string) ([]models.Investor, error)
	}
}

func (env Env) investorsByCpf(w http.ResponseWriter, r *http.Request) {
	cpf := r.PathValue("cpf")

	invstrs, err := env.investors.ByCpf(cpf)
	if err != nil {
		log.Print(err)
		http.Error(w, http.StatusText(500), 500)
		return
	}

	invstrsJson, err := json.Marshal(invstrs)
	if err != nil {
		log.Print(err)
		http.Error(w, http.StatusText(500), 500)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.Write(invstrsJson)
}
