package models

import (
	"time"
)

type Investment struct {
	Id            int       `json:"id"`
	InitialAmount int       `json:"amount" validate:"required,gt=0"`
	Balance       int       `json:"balance"`
	CreationDate  time.Time `json:"creation_date" validate:"required"`
	Investor      Investor  `json:"investor" validate:"required"`
}

type InvestmentCreationDTO struct {
	InitialAmount int    `json:"amount" validate:"required,gt=0"`
	CreationDate  string `json:"creation_date" validate:"required,datetime=2006-01-02"`
	InvestorCPF   string `json:"investor_cpf" validate:"required,cpf"`
}

type InvestmentModel struct {
	DB Database
}

func (m InvestmentModel) Create(inv InvestmentCreationDTO) error {
	_, err := m.DB.Exec(
		"INSERT INTO investments (initial_amount, balance, creation_date, investor_cpf) VALUES (?, ?, ?, ?)",
		inv.InitialAmount,
		inv.InitialAmount,
		inv.CreationDate,
		inv.InvestorCPF,
	)
	if err != nil {
		return err
	}

	return nil
}
