package handlers

import (
	"bytes"
	"causeurgnocchi/backend-test/models"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"
)

var investment = &models.Investment{
	Id:            1,
	InitialAmount: 1000000,
	Balance:       1000000,
	CreationDate:  time.Now(),
	Investor:      *investor,
}

type mockInvestmentModel struct {
}

func (m mockInvestmentModel) Create(models.InvestmentCreationDTO) (int, error) {
	return 1, nil
}

func (m mockInvestmentModel) ById(id int) (*models.Investment, error) {
	return investment, nil
}

func configInvestmentTest(t *testing.T) {
	t.Helper()

	h := InvestmentHandler{Investments: mockInvestmentModel{}}

	mux = http.NewServeMux()

	mux.HandleFunc("/api/investments", h.CreateInvestment)
	mux.HandleFunc("/api/investments/{id}", h.FindInvestmentById)
}

func TestInvestmentsCreate(t *testing.T) {
	configInvestmentTest(t)

	dto := &models.InvestmentCreationDTO{
		InitialAmount: 100000,
		CreationDate:  time.Now(),
		InvestorCPF:   "95130357000",
	}

	dtoJson, _ := json.Marshal(dto)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("POST", "/api/investments", bytes.NewBuffer(dtoJson))

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	investmentJson, _ := json.Marshal(investment)

	if body := rec.Body.String(); body != string(investmentJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(investmentJson), body)
	}
}

func TestInvestmentsById(t *testing.T) {
	configInvestmentTest(t)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("GET", "/api/investments/1", nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	investmentJson, _ := json.Marshal(investment)

	if body := rec.Body.String(); body != string(investmentJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(investmentJson), body)
	}
}
