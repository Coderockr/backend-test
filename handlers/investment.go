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
	"time"

	"github.com/go-playground/validator/v10"
	"github.com/go-sql-driver/mysql"
)

type InvestmentHandler struct {
	Investments interface {
		Create(inv models.InvestmentCreationDTO) (int, error)
		ById(id int) (*models.Investment, error)
		ByInvestorCpf(cpf string) ([]models.Investment, error)
	}
}

func (h InvestmentHandler) CreateInvestment(w http.ResponseWriter, r *http.Request) {
	if r.Method != http.MethodPost {
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}

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

	id, err := h.Investments.Create(dto)
	if err != nil {
		if err.(*mysql.MySQLError).Number == 1452 {
			msg := fmt.Sprintf("There are no records of an investor with CPF of %s", dto.InvestorCPF)
			http.Error(w, msg, http.StatusBadRequest)
		} else {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		}

		return
	}

	i, err := h.Investments.ById(id)
	if err != nil {
		log.Print(err)
		http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

		return
	}

	iJson, _ := json.Marshal(i)

	w.Header().Set("Content-Type", "application/json")
	w.Write(iJson)
}

func (h InvestmentHandler) FindInvestmentById(w http.ResponseWriter, r *http.Request) {
	pv := r.PathValue("id")

	id, err := strconv.Atoi(pv)
	if err != nil {
		var msg string

		if errors.Is(err, strconv.ErrSyntax) {
			msg = fmt.Sprintf("Investment ID of value %s has a syntax error", pv)
			http.Error(w, msg, http.StatusBadRequest)
		} else if errors.Is(err, strconv.ErrRange) {
			msg = fmt.Sprintf("Investment ID of value %s is out of range", pv)
			http.Error(w, msg, http.StatusBadRequest)
		} else {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		}

		return
	}

	i, err := h.Investments.ById(id)
	if err != nil {
		if errors.Is(err, sql.ErrNoRows) {
			msg := fmt.Sprintf("No record of an investment with id %d has been found", id)
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

func (h InvestmentHandler) FilterByInvestorCpf(w http.ResponseWriter, r *http.Request) {
	cpf := r.URL.Query().Get("investor_cpf")

	if !cpfIsValid(cpf) {
		msg := "CPF provided is invalid"
		http.Error(w, msg, http.StatusBadRequest)
		return
	}

	investments, err := h.Investments.ByInvestorCpf(cpf)
	if errors.Is(err, sql.ErrNoRows) {
		msg := fmt.Sprintf("Investor of CPF %s not found", cpf)
		http.Error(w, msg, http.StatusNotFound)
		return
	}

	if len(investments) == 0 {
		msg := fmt.Sprintf("Investor of CPF %s doesn't have any investment", cpf)
		http.Error(w, msg, http.StatusNotFound)
		return
	}

	investmentsJson, err := json.Marshal(investments)
	if err != nil {
		http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		log.Print(err)
		return
	}

	w.Header().Set("Content-Type", "application/json")
	w.Write(investmentsJson)
}

func notFuture(fl validator.FieldLevel) bool {
	today := time.Now()
	creationDate := fl.Field().Interface().(models.Date)
	return !creationDate.After(today)
}
