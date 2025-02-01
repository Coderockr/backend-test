package models

import (
	"database/sql"
	"testing"

	_ "github.com/go-sql-driver/mysql"
)

func TestInvestors(t *testing.T) {
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

	m := &InvestorModel{Db: tx}

	i := Investor{
		Cpf:  "92087347069",
		Name: "Lazlo Varga Jr",
	}

	err = m.Create(i)
	if err != nil {
		t.Errorf("Error creating investor:\n%s", err.Error())
	}

	_, err = m.ByCpf(i.Cpf)
	if err != nil {
		t.Errorf("Error retrieving investor:\n%s", err.Error())
	}
}
