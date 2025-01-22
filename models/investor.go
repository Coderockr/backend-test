package models

import (
	"database/sql"
)

type Investor struct {
	CPF  string `json:"cpf"`
	Name string `json:"name"`
}

type InvestorModel struct {
	DB *sql.DB
}

func (m InvestorModel) Create(invstr Investor) error {
	_, err := m.DB.Exec("INSERT INTO investors VALUES (?, ?)", invstr.CPF, invstr.Name);
	if err != nil {
		return err
	}

	return nil
}

func (m InvestorModel) ByCpf(cpf string) ([]Investor, error) {
	rows, err := m.DB.Query("SELECT * FROM investors where cpf = ?", cpf)
	if err != nil {
		return nil, err
	}

	var invstrs []Investor

	for rows.Next() {
		var invstr Investor

		err := rows.Scan(&invstr.CPF, &invstr.Name)
		if err != nil {
			return nil, err
		}

		invstrs = append(invstrs, invstr)
	}
	if err = rows.Err(); err != nil {
		return nil, err
	}

	return invstrs, nil
}
