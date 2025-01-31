package models

import (
	"time"
)

type Investment struct {
	Id            int       `json:"id"`
	InitialAmount int       `json:"amount"`
	Balance       int       `json:"balance"`
	CreationDate  time.Time `json:"creation_date"`
	Investor      Investor  `json:"investor"`
}

type InvestmentCreationDTO struct {
	InitialAmount int       `json:"amount" validate:"required,gt=0"`
	CreationDate  time.Time `json:"creation_date" validate:"required,notfuture"`
	InvestorCPF   string    `json:"investor_cpf" validate:"required"`
}

type InvestmentModel struct {
	Db database
}

func (m InvestmentModel) Create(dto InvestmentCreationDTO) (int, error) {
	r, err := m.Db.Exec(
		"INSERT INTO investments (initial_amount, balance, creation_date, investor_cpf) VALUES (?, ?, ?, ?)",
		dto.InitialAmount,
		dto.InitialAmount,
		dto.CreationDate,
		dto.InvestorCPF,
	)
	if err != nil {
		return -1, err
	}

	id, err := r.LastInsertId()
	if err != nil {
		return -1, err
	}

	return int(id), nil
}

func (m InvestmentModel) ById(id int) (*Investment, error) {
	var investment Investment
	var cpf string

	r := m.Db.QueryRow("SELECT id, initial_amount, balance, creation_date, investor_cpf FROM investments WHERE id = ?", id)

	err := r.Scan(&investment.Id, &investment.InitialAmount, &investment.Balance, &investment.CreationDate, &cpf)
	if err != nil {
		return nil, err
	}

	investorM := &InvestorModel{Db: m.Db}

	investor, err := investorM.ByCpf(cpf)
	if err != nil {
		return nil, err
	}

	investment.Investor = *investor
	return &investment, nil
}

func (m InvestmentModel) RemoveBalance(id int) error {
	_, err := m.Db.Exec("UPDATE investments SET balance = 0 WHERE id = ?", id)

	return err
}
