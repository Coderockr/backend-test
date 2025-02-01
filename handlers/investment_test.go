package handlers

import (
	"bytes"
	"causeurgnocchi/backend-test/models"
	"encoding/json"
	"fmt"
	"net/http"
	"net/http/httptest"
	"testing"
	"time"
)

var investment = &models.Investment{
	Id:            1,
	InitialAmount: 1000000,
	Balance:       1000000,
	CreationDate:  models.Date{Time: time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC)},
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

func (m mockInvestmentModel) ByInvestorCpf(cpf string) ([]models.Investment, error) {
	return []models.Investment{*investment}, nil
}

func (m mockInvestmentModel) RemoveBalance(id int) error {
	return nil
}

func configInvestmentTest(t *testing.T) {
	t.Helper()

	h := InvestmentHandler{Investments: mockInvestmentModel{}}

	mux = http.NewServeMux()

	mux.HandleFunc("GET /api/investments/{id}", h.FindInvestmentById)
	mux.HandleFunc("GET /api/investments", h.FilterByInvestorCpf)
	mux.HandleFunc("POST /api/investments", h.CreateInvestment)
}

func TestInvestmentsCreate(t *testing.T) {
	configInvestmentTest(t)

	dtoJson := []byte(fmt.Sprintf(`{"initial_amount":1000000,"creation_date":"2025-01-01","investor_cpf":"%s"}`, investor.Cpf))

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

func TestInvestmentsByInvestorCpf(t *testing.T) {
	configInvestmentTest(t)

	rec := httptest.NewRecorder()
	reqUrl := fmt.Sprintf("/api/investments?investor_cpf=%s", investor.Cpf)
	req := httptest.NewRequest("GET", reqUrl, nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	investmentsJson, _ := json.Marshal([]models.Investment{*investment})

	if body := rec.Body.String(); body != string(investmentsJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(investmentsJson), body)
	}
}
