package main

import (
	"bytes"
	"causeurgnocchi/backend-test/models"
	"database/sql"
	"encoding/json"
	"net/http"
	"net/http/httptest"
	"testing"
)

var mux *http.ServeMux
var env *Env

func ConfigTest(t *testing.T) {
	t.Helper()

	db, err := sql.Open("mysql", "root:example@(127.0.0.1:3306)/investments")
	if err != nil {
		t.Fatal(err)
	}

	db.SetMaxIdleConns(1)
	db.SetMaxOpenConns(1)

	tx, err := db.Begin()
	if err != nil {
		t.Fatal(err)
	}

	env = &Env{investors: &models.InvestorModel{DB: tx}}

	mux = http.NewServeMux()
	mux.HandleFunc("/api/investors", env.investorsIndex)

	t.Cleanup(func() {
		tx.Rollback()
		db.Close()
	})
}

func TestInvestorsCreate(t *testing.T) {
	ConfigTest(t)

	invstr := &models.Investor{
		CPF:  "95130357000",
		Name: "Lazlo Varga",
	}
	invstrJson, _ := json.Marshal(invstr)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("POST", "/api/investors", bytes.NewBuffer(invstrJson))

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	if body := rec.Body.String(); body != string(invstrJson) {
		t.Errorf("Expected investor that was created. Got %s", body)
	}
}

func TestInvestorsByCPF(t *testing.T) {
	ConfigTest(t)

	const CPF = "95130357000"

	invstr := models.Investor{
		CPF:  CPF,
		Name: "Lazlo Varga",
	}

	env.investors.Create(invstr)

	rec := httptest.NewRecorder()
	req := httptest.NewRequest("GET", "/api/investors?cpf="+CPF, nil)

	mux.ServeHTTP(rec, req)

	if rec.Code != http.StatusOK {
		t.Errorf("Expected response code %d. Got %d", http.StatusOK, rec.Code)
	}

	invstrJson, _ := json.Marshal(invstr)
	if body := rec.Body.String(); body != string(invstrJson) {
		t.Errorf("Expected investor of CPF %s. Got %s", CPF, body)
	}
}
