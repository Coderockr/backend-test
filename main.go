package main

import (
	"database/sql"
	"encoding/json"
	"errors"
	"log"
	"net/http"
	"strconv"

	"causeurgnocchi/backend-test/models"

	"github.com/go-playground/validator/v10"
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

		v := validator.New()
		v.RegisterValidation("cpf", validateCPF)

		err = v.Struct(invstr)
		if err != nil {
			log.Print(err)
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

func validateCPF(fl validator.FieldLevel) bool {
	f := fl.Field().String()

	if len(f) != 11 {
		return false
	}

	var cpf [11]int
	for i, c := range f {
		n, err := strconv.Atoi(string(c))
		if err != nil {
			log.Print(err.Error())
		}
		cpf[i] = n
	}

	sum1 := 0
	for i := 0; i < 9; i++ {
		sum1 += cpf[i] * (10 - i)
	}

	validation1 := (sum1 * 10) % 11
	if validation1 == 10 {
		validation1 = 0
	}
	if validation1 != cpf[9] {
		return false
	}

	sum2 := validation1 * 2
	for i := 0; i < 9; i++ {
		sum2 += cpf[i] * (11 - i)
	}

	validation2 := (sum2 * 10) % 11
	if validation2 == 10 {
		validation2 = 0
	}
	if validation2 != cpf[10] {
		return false
	}

	return true
}
