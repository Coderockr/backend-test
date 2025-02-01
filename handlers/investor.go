package handlers

import (
	"causeurgnocchi/backend-test/models"
	"database/sql"
	"encoding/json"
	"errors"
	"fmt"
	"log"
	"net/http"

	"github.com/go-playground/validator/v10"
	"github.com/go-sql-driver/mysql"
)

type InvestorHandler struct {
	Investors interface {
		Create(invstr models.Investor) error
		ByCpf(cpf string) (*models.Investor, error)
	}
}

func (h InvestorHandler) CreateInvestor(w http.ResponseWriter, r *http.Request) {
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
		if err.(*mysql.MySQLError).Number == 1062 {
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
