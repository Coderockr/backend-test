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
	Date:        time.Now(),
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

	h := WithdrawalHandler{Withdrawals: mockWithdrawalModel{}}

	mux = http.NewServeMux()

	mux.HandleFunc("/api/withdrawals", h.CreateWithdrawal)
	mux.HandleFunc("/api/withdrawals/{id}", h.FindWithdrawalById)
}

func TestWithdrawalsCreate(t *testing.T) {
	configWithdrawalTest(t)

	dto := &models.WithdrawalCreationDTO{
		InvestmentId: 1,
		Date:         time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC),
	}

	dtoJson, _ := json.Marshal(dto)

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
