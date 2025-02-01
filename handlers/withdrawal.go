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
)

type WithdrawalHandler struct {
	Withdrawals interface {
		Create(w models.WithdrawalCreationDTO) (int, error)
		ById(id int) (*models.Withdrawal, error)
	}

	Investments interface {
		RemoveBalance(id int) error
	}
}

func (h WithdrawalHandler) CreateWithdrawal(w http.ResponseWriter, r *http.Request) {
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

	id, err := h.Withdrawals.Create(dto)
	if err != nil {
		if errors.Is(err, models.ErrInvalidWithdrawalDate) {
			http.Error(w, err.Error(), http.StatusBadRequest)
		} else {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		}

		return
	}

	err = h.Investments.RemoveBalance(dto.InvestmentId)
	if err != nil {
		log.Print(err)
		http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		return
	}

	withdrawal, err := h.Withdrawals.ById(id)
	if err != nil {
		log.Print(err)
		http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		return
	}

	withdrawalJson, _ := json.Marshal(withdrawal)

	w.Header().Set("Content-Type", "application/json")
	w.Write(withdrawalJson)
}

func (h WithdrawalHandler) FindWithdrawalById(w http.ResponseWriter, r *http.Request) {
	pv := r.PathValue("id")

	id, err := strconv.Atoi(pv)
	if err != nil {
		var msg string

		if errors.Is(err, strconv.ErrSyntax) {
			msg = fmt.Sprintf("Withdrawal ID of value %s has a syntax error", pv)
			http.Error(w, msg, http.StatusBadRequest)
		} else if errors.Is(err, strconv.ErrRange) {
			msg = fmt.Sprintf("Withdrawal ID of value %s is out of range", pv)
			http.Error(w, msg, http.StatusBadRequest)
		} else {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		}
		return
	}

	withdrawal, err := h.Withdrawals.ById(id)
	if err != nil {
		if errors.Is(err, sql.ErrNoRows) {
			msg := fmt.Sprintf("No record of a withdrawal with id %d has been found", id)
			http.Error(w, msg, http.StatusNotFound)
		} else {
			log.Print(err)
			http.Error(w, http.StatusText(http.StatusInternalServerError), http.StatusInternalServerError)
		}
		return
	}

	withdrawalJson, _ := json.Marshal(withdrawal)

	w.Header().Set("Content-Type", "application/json")
	w.Write(withdrawalJson)
}
