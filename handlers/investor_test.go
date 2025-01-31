package handlers

import (
	"bytes"
	"causeurgnocchi/backend-test/models"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"testing"
)

var investorJson []byte

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

	investorJson, _ = json.Marshal(investor)

	h := InvestorHandler{Investors: mockInvestorModel{}}

	mux = http.NewServeMux()

	mux.HandleFunc("/api/investors", h.CreateInvestor)
	mux.HandleFunc("/api/investors/{cpf}", h.FindInvestorByCpf)
}

func TestInvestorsCreate(t *testing.T) {
	configInvestorTest(t)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("POST", "/api/investors", bytes.NewBuffer(investorJson))

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d.\nGot %d", http.StatusOK, rec.Code)
	}

	if body := rec.Body.String(); body != string(investorJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(investorJson), body)
	}
}

func TestInvestorsByCPF(t *testing.T) {
	configInvestorTest(t)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("GET", "/api/investors/"+cpf, nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d.\nGot %d", http.StatusOK, rec.Code)
	}

	if body := rec.Body.String(); body != string(investorJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(investorJson), body)
	}
}
