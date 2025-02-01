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
		Cpf:  "92087347069",
		Name: "Lazlo Varga Jr",
	}

	investorM.Create(investor)

	investment := InvestmentCreationDTO{
		InitialAmount: 1000000,
		CreationDate:  Date{Time: time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC)},
		InvestorCPF:   "92087347069",
	}

	id, err := investmentM.Create(investment)
	if err != nil {
		t.Errorf("Error creating investment:\n%s", err.Error())
	}

	_, err = investmentM.ById(id)
	if err != nil {
		t.Errorf("Error retrieving investment:\n%s", err.Error())
	}

	_, err = investmentM.ByInvestorCpf(investor.Cpf)
	if err != nil {
		t.Errorf("Error retrieving investments belonging to investor of CPF %s:\n%s", investor.Cpf, err.Error())
	}

	err = investmentM.RemoveBalance(id)
	if err != nil {
		t.Errorf("Error removing investment's balance:\n%s", err.Error())
	}
}
