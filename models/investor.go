package models

import (
	"database/sql"
)

type Investor struct {
	CPF  string `json:"cpf" validate:"cpf"`
	Name string `json:"name" validate:"required"`
}

type InvestorModel struct {
	DB Database
}

func (m InvestorModel) Create(invstr Investor) error {
	_, err := m.DB.Exec("INSERT INTO investors VALUES (?, ?)", invstr.CPF, invstr.Name)
	if err != nil {
		return err
	}

	return nil
}

func (m InvestorModel) ByCPF(cpf string) (*Investor, error) {
	var invstr Investor

	err := m.DB.QueryRow("SELECT * FROM investors where cpf = ?", cpf).Scan(&invstr.CPF, &invstr.Name)
	if err != nil {
		return nil, err
	}

	return &invstr, nil
}

type Database interface {
	Query(query string, args ...interface{}) (*sql.Rows, error)

	QueryRow(query string, args ...interface{}) *sql.Row

	Exec(query string, args ...interface{}) (sql.Result, error)
}
