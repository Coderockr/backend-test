package models

import (
	"math"
	"strconv"
	"time"
)

type Withdrawal struct {
	Id          int        `json:"id"`
	GrossAmount int        `json:"gross_amount"`
	NetAmount   int        `json:"net_amount"`
	Date        time.Time  `json:"date"`
	Investment  Investment `json:"investment"`
}

type WithdrawalCreationDTO struct {
	Date         time.Time `validate:"required,datetime=2006-01-02"`
	InvestmentId int
}

type WithdrawalModel struct {
	Db Database
}

func (m WithdrawalModel) Create(dto WithdrawalCreationDTO) (int, error) {
	im := &InvestmentModel{Db: m.Db}

	inv, err := im.ById(dto.InvestmentId)
	if err != nil {
		return -1, err
	}

	var taxes int
	gain := inv.Balance - inv.InitialAmount

	if inv.CreationDate.Before(inv.CreationDate.AddDate(1, 0, 0)) {
		taxes = int(math.Floor(float64(gain) * 0.225))
	} else if inv.CreationDate.Before(inv.CreationDate.AddDate(2, 0, 0)) {
		taxes = int(math.Floor(float64(gain) * 0.185))
	} else {
		taxes = int(math.Floor(float64(gain) * 0.15))
	}

	r, err := m.Db.Exec(
		"INSERT INTO withdrawals (gross_amount, net_amount, date, investment_id) VALUES (?, ?, ?, ?)",
		inv.Balance,
		inv.Balance-taxes,
		dto.Date,
		dto.InvestmentId,
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

func (m WithdrawalModel) ById(id int) (*Withdrawal, error) {
	var w Withdrawal
	var investmentIdStr string

	r := m.Db.QueryRow("SELECT id, gross_amount, net_amount, date, investment_id FROM withdrawals WHERE id = ?", id)

	err := r.Scan(&w.Id, &w.GrossAmount, &w.NetAmount, &w.Date, &investmentIdStr)
	if err != nil {
		return nil, err
	}

	investmentM := &InvestmentModel{Db: m.Db}
	investmentId, _ := strconv.Atoi(investmentIdStr)

	investment, err := investmentM.ById(investmentId)
	if err != nil {
		return nil, err
	}

	w.Investment = *investment
	return &w, nil
}
