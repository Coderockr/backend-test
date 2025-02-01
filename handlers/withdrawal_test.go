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

var withdrawal = &models.Withdrawal{
	Id:          1,
	GrossAmount: 1000000,
	NetAmount:   1000000,
	Date:        models.Date{Time: time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC)},
	Investment:  *investment,
}

type mockWithdrawalModel struct {
}

func (m mockWithdrawalModel) Create(models.WithdrawalCreationDTO) (int, error) {
	return 1, nil
}

func (m mockWithdrawalModel) ById(id int) (*models.Withdrawal, error) {
	return withdrawal, nil
}

func configWithdrawalTest(t *testing.T) {
	t.Helper()

	h := WithdrawalHandler{
		Withdrawals: mockWithdrawalModel{},
		Investments: mockInvestmentModel{},
	}

	mux = http.NewServeMux()

	mux.HandleFunc("GET /api/withdrawals/{id}", h.FindWithdrawalById)
	mux.HandleFunc("POST /api/withdrawals", h.CreateWithdrawal)
}

func TestWithdrawalsCreate(t *testing.T) {
	configWithdrawalTest(t)

	dtoJson := []byte(`{"date":"2025-01-01","investment_id":1}`)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("POST", "/api/withdrawals", bytes.NewBuffer(dtoJson))

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	withdrawalJson, _ := json.Marshal(withdrawal)

	if body := rec.Body.String(); body != string(withdrawalJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(withdrawalJson), body)
	}
}

func TestWithdrawalsById(t *testing.T) {
	configWithdrawalTest(t)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("GET", "/api/withdrawals/1", nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	withdrawalJson, _ := json.Marshal(withdrawal)

	if body := rec.Body.String(); body != string(withdrawalJson) {
		t.Errorf("Expected the following reponse body:\n%s.\nGot\n%s", string(withdrawalJson), body)
	}
}
