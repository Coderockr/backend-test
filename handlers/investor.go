package handlers

import (
	"causeurgnocchi/backend-test/models"
	"database/sql"
	"encoding/json"
	"errors"
	"fmt"
	"log"
	"net/http"
	"strconv"

	"github.com/go-playground/validator/v10"
	"github.com/go-sql-driver/mysql"
)

const mySqlKeyExists = 1062

type InvestorHandler struct {
	Investors interface {
		Create(invstr models.Investor) error
		ByCpf(cpf string) (*models.Investor, error)
	}
}

func (h InvestorHandler) CreateInvestor(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}

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

		return cpfIsValid(cpf)
	})

	err = v.Struct(i)
	if err != nil {
		errs := err.(validator.ValidationErrors)
		msg := fmt.Sprintf("Invalid investor information:\n%s", errs)
		http.Error(w, msg, http.StatusBadRequest)

		return
	}

	err = h.Investors.Create(i)
	if err != nil {
		if err.(*mysql.MySQLError).Number == mySqlKeyExists {
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
}

func (h InvestorHandler) FindInvestorByCpf(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodGet {
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}

	cpf := r.PathValue("cpf")

	if !cpfIsValid(cpf) {
		http.Error(w, "Invalid CPF", http.StatusBadRequest)
		return
	}

	i, err := h.Investors.ByCpf(cpf)
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
}

func cpfIsValid(cpf string) bool {
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
