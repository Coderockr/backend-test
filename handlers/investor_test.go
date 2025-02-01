package handlers

import (
	"bytes"
	"causeurgnocchi/backend-test/models"
	"encoding/json"
	"fmt"
	"net/http"
	"net/http/httptest"
	"testing"
)

var (
	mux *http.ServeMux

	investor = &models.Investor{
		Cpf:  "92087347069",
		Name: "Lazlo Varga Jr",
	}
)

type mockInvestorModel struct {
}

func (m mockInvestorModel) Create(models.Investor) error {
	return nil
}

func (m mockInvestorModel) ByCpf(cpf string) (*models.Investor, error) {
	return investor, nil
}

func configInvestorTest(t *testing.T) {
	t.Helper()

	h := InvestorHandler{Investors: mockInvestorModel{}}

	mux = http.NewServeMux()

	mux.HandleFunc("GET /api/investors/{cpf}", h.FindInvestorByCpf)
	mux.HandleFunc("POST /api/investors", h.CreateInvestor)
}

func TestInvestorsCreate(t *testing.T) {
	configInvestorTest(t)

	reqBody := []byte(fmt.Sprintf(`{"cpf":"%s","name":"%s"}`, investor.Cpf, investor.Name))

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("POST", "/api/investors", bytes.NewBuffer(reqBody))

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d.\nGot %d", http.StatusOK, rec.Code)
	}

	if b := rec.Body.String(); b != string(reqBody) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(reqBody), b)
	}
}

func TestInvestorsByCPF(t *testing.T) {
	configInvestorTest(t)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("GET", "/api/investors/"+investor.Cpf, nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d.\nGot %d", http.StatusOK, rec.Code)
	}

	expected, _ := json.Marshal(investor)

	if b := rec.Body.String(); b != string(expected) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(expected), b)
	}
}
