package main

import (
	"database/sql"
	"encoding/json"
	"errors"
	"fmt"
	"log"
	"net/http"
	"strconv"
	"time"

	"causeurgnocchi/backend-test/models"

	"github.com/go-playground/validator/v10"
	"github.com/go-sql-driver/mysql"
)

const MYSQL_KEY_EXITS = 1062

func main() {
	db, err := sql.Open("mysql", "root:example@(127.0.0.1:3306)/investments?parseTime=true")
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	env := &Env{
		investors:   &models.InvestorModel{Db: db},
		investments: &models.InvestmentModel{Db: db},
		withdrawals: &models.WithdrawalModel{Db: db},
	}

	http.HandleFunc("/api/investors", env.investorsIndex)
	http.HandleFunc("/api/investments", env.investmentsIndex)
	http.HandleFunc("/api/witdrawals", env.withdrawalsIndex)

	http.ListenAndServe(":8080", nil)
}

type Env struct {
	investors interface {
		Create(invstr models.Investor) error
		ByCpf(cpf string) (*models.Investor, error)
	}

	investments interface {
		Create(inv models.InvestmentCreationDTO) (int, error)
		ById(id int) (*models.Investment, error)
	}

	withdrawals interface {
		Create(w models.WithdrawalCreationDTO) (int, error)
		ById(id int) (*models.Withdrawal, error)
	}
}

func (env Env) investorsIndex(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodPost:
		var i models.Investor

		err := decodeJsonBody(w, r, &i)
		if err != nil {
			var mr *malformedRequest

			if errors.As(err, &mr) {
				http.Error(w, mr.msg, mr.status)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		v := validator.New()
		v.RegisterValidation("cpf", func(fl validator.FieldLevel) bool {
			cpf := fl.Field().String()

			return validateCPF(cpf)
		})

		err = v.Struct(i)
		if err != nil {
			errs := err.(validator.ValidationErrors)
			msg := fmt.Sprintf("Invalid investor information:\n%s", errs)
			http.Error(w, msg, http.StatusBadRequest)

			return
		}

		err = env.investors.Create(i)
		if err != nil {
			if err.(*mysql.MySQLError).Number == MYSQL_KEY_EXITS {
				msg := "CPF provided is already being used"
				http.Error(w, msg, http.StatusBadRequest)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		iJson, err := json.Marshal(i)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

			return
		}

		w.Header().Set("Content-Type", "application/json")
		w.Write(iJson)

	case http.MethodGet:
		cpf := r.URL.Query().Get("cpf")

		if !validateCPF(cpf) {
			http.Error(w, "Invalid CPF", http.StatusBadRequest)
		}

		i, err := env.investors.ByCpf(cpf)
		if err != nil {
			if errors.Is(err, sql.ErrNoRows) {
				msg := fmt.Sprintf("No record of an investor with CPF %s has been found", cpf)
				http.Error(w, msg, http.StatusNotFound)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		iJson, _ := json.Marshal(i)

		w.Header().Set("Content-Type", "application/json")
		w.Write(iJson)

	default:
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}
}

func (env Env) investmentsIndex(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodPost:
		var dto models.InvestmentCreationDTO

		err := decodeJsonBody(w, r, &dto)
		if err != nil {
			var mr *malformedRequest

			if errors.As(err, &mr) {
				http.Error(w, mr.msg, mr.status)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		v := validator.New()
		v.RegisterValidation("notfuture", notFuture)

		err = v.Struct(dto)
		if err != nil {
			errs := err.(validator.ValidationErrors)
			msg := fmt.Sprintf("Invalid investment information:\n%s", errs)
			http.Error(w, msg, http.StatusBadRequest)

			return
		}

		id, err := env.investments.Create(dto)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

			return
		}

		i, err := env.investments.ById(id)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

			return
		}

		iJson, _ := json.Marshal(i)

		w.Header().Set("Content-Type", "application/json")
		w.Write(iJson)

	case http.MethodGet:
		queryParam := r.URL.Query().Get("id")

		id, err := strconv.Atoi(queryParam)
		if err != nil {
			var msg string

			if errors.Is(err, strconv.ErrSyntax) {
				msg = fmt.Sprintf("Investment ID of value %s has a syntax error", queryParam)
				http.Error(w, msg, http.StatusBadRequest)
			} else if errors.Is(err, strconv.ErrRange) {
				msg = fmt.Sprintf("Investment ID of value %s is out of range", queryParam)
				http.Error(w, msg, http.StatusBadRequest)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		inv, err := env.investments.ById(id)
		if err != nil {
			if errors.Is(err, sql.ErrNoRows) {
				msg := fmt.Sprintf("No record of an investor with id %d has been found", id)
				http.Error(w, msg, http.StatusNotFound)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		invJson, _ := json.Marshal(inv)

		w.Header().Set("Content-Type", "application/json")
		w.Write(invJson)

	default:
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}
}

func (env Env) withdrawalsIndex(w http.ResponseWriter, r *http.Request) {
	switch r.Method {
	case http.MethodPost:
		var dto models.WithdrawalCreationDTO

		err := decodeJsonBody(w, r, &dto)
		if err != nil {
			var mr *malformedRequest

			if errors.As(err, &mr) {
				http.Error(w, mr.msg, mr.status)
			} else {
				log.Print(err)
				http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
			}

			return
		}

		v := validator.New()

		err = v.Struct(dto)
		if err != nil {
			errs := err.(validator.ValidationErrors)
			msg := fmt.Sprintf("Invalid withdrawal information:\n%s", errs)
			http.Error(w, msg, http.StatusBadRequest)

			return
		}

		id, err := env.withdrawals.Create(dto)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

			return
		}

		withdrawal, err := env.withdrawals.ById(id)
		if err != nil {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

			return
		}

		withdrawalJson, _ := json.Marshal(withdrawal)

		w.Header().Set("Content-Type", "application/json")
		w.Write(withdrawalJson)
	}
}

func validateCPF(cpf string) bool {
	if len(cpf) != 11 {
		return false
	}

	var cpfDigits [11]int
	for i, c := range cpf {
		n, err := strconv.Atoi(string(c))
		if err != nil {
			log.Print(err.Error())
		}
		cpfDigits[i] = n
	}

	sum1 := 0
	for i := 0; i < 9; i++ {
		sum1 += cpfDigits[i] * (10 - i)
	}

	validator1 := (sum1 * 10) % 11
	if validator1 == 10 {
		validator1 = 0
	}
	if validator1 != cpfDigits[9] {
		return false
	}

	sum2 := validator1 * 2
	for i := 0; i < 9; i++ {
		sum2 += cpfDigits[i] * (11 - i)
	}

	validator2 := (sum2 * 10) % 11
	if validator2 == 10 {
		validator2 = 0
	}
	if validator2 != cpfDigits[10] {
		return false
	}

	return true
}

func notFuture(fl validator.FieldLevel) bool {
	today := time.Now().Truncate(24 * time.Hour)

	creationDate, err := time.Parse(time.DateOnly, fl.Field().String())
	if err != nil {
		log.Print(err)
		return false
	}

	return !creationDate.After(today)
}
