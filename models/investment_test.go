package models

import (
	"database/sql"
	"testing"
	"time"

	_ "github.com/go-sql-driver/mysql"
)

func TestInvestments(t *testing.T) {
	db, err := sql.Open("mysql", "root:example@(127.0.0.1:3306)/investments?parseTime=true")
	if err != nil {
		t.Fatal(err)
	}
	defer db.Close()

	tx, err := db.Begin()
	if err != nil {
		t.Fatal(err)
	}
	defer tx.Rollback()

	investorM := InvestorModel{Db: tx}
	investmentM := InvestmentModel{Db: tx}

	investor := Investor{
		Cpf:  "95130357000",
		Name: "Lazlo Varga",
	}

	investorM.Create(investor)

	investment := InvestmentCreationDTO{
		InitialAmount: 1000000,
		CreationDate:  Date{Time: time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC)},
		InvestorCPF:   "95130357000",
	}

	id, err := investmentM.Create(investment)
	if err != nil {
		t.Errorf("Error creating investment:\n%s", err.Error())
	}

	_, err = investmentM.ById(id)
	if err != nil {
		t.Errorf("Error retrieving investment:\n%s", err.Error())
	}
}
