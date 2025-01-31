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
)

type InvestmentHandler struct {
	Investments interface {
		Create(inv models.InvestmentCreationDTO) (int, error)
		ById(id int) (*models.Investment, error)
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
		log.Print(err)
		http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)

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
	if r.Method != http.MethodGet {
		http.Error(w, http.StatusText(http.StatusMethodNotAllowed), http.StatusMethodNotAllowed)
	}

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

func notFuture(fl validator.FieldLevel) bool {
	today := time.Now()
	creationDate := fl.Field().Interface().(models.Date)

	return !creationDate.After(today)
}
