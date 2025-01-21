package models

import "database/sql"

type Investor struct {
	Id int
	Name string
	Cpf string
}

type InvestorModel struct {
	db *sql.DB
}

func (m InvestorModel) ByCpf(cpf string) {
	m.db.Query("SELECT * FROM investors where cpf = ?", cpf)
}
