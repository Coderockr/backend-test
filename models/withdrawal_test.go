package models

import (
	"database/sql"
	"testing"
	"time"

	_ "github.com/go-sql-driver/mysql"
)

func TestWithdrawalsCreate(t *testing.T) {
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
	withdrawalM := WithdrawalModel{Db: tx}

	investor := Investor{
		Cpf:  "95130357000",
		Name: "Lazlo Varga",
	}

	investorM.Create(investor)

	investment := InvestmentCreationDTO{
		InitialAmount: 1000000,
		CreationDate:  time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC),
		InvestorCPF:   "95130357000",
	}

	investmentId, _ := investmentM.Create(investment)

	w := WithdrawalCreationDTO{
		Date:         time.Date(2025, 1, 1, 0, 0, 0, 0, time.UTC),
		InvestmentId: investmentId,
	}

	withdrawalId, err := withdrawalM.Create(w)
	if err != nil {
		t.Errorf("Error creating withdrawal:\n%s", err.Error())
	}

	_, err = withdrawalM.ById(withdrawalId)
	if err != nil {
		t.Errorf("Error getting withdrawal:\n%s", err.Error())
	}
}
