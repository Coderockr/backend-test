package models

type Investment struct {
	Id            int      `json:"id"`
	InitialAmount int      `json:"initial_amount"`
	Balance       int      `json:"balance"`
	CreationDate  Date     `json:"creation_date"`
	Investor      Investor `json:"investor"`
}

type InvestmentCreationDTO struct {
	InitialAmount int    `json:"initial_amount" validate:"required,gt=0"`
	CreationDate  Date   `json:"creation_date" validate:"required,notfuture"`
	InvestorCPF   string `json:"investor_cpf" validate:"required"`
}

type InvestmentModel struct {
	Db database
}

func (m InvestmentModel) Create(dto InvestmentCreationDTO) (int, error) {
	r, err := m.Db.Exec(
		"INSERT INTO investments (initial_amount, balance, creation_date, investor_cpf) VALUES (?, ?, ?, ?)",
		dto.InitialAmount,
		dto.InitialAmount,
		dto.CreationDate.Time,
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

	err := r.Scan(&investment.Id, &investment.InitialAmount, &investment.Balance, &investment.CreationDate.Time, &cpf)
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

func (m InvestmentModel) ByInvestorCpf(cpf string) ([]Investment, error) {
	investorM := &InvestorModel{Db: m.Db}

	investor, err := investorM.ByCpf(cpf)
	if err != nil {
		return nil, err
	}

	r, err := m.Db.Query("SELECT id, initial_amount, balance, creation_date FROM investments WHERE investor_cpf = ?", cpf)
	if err != nil {
		return nil, err
	}

	var investements []Investment

	for r.Next() {
		var i Investment

		err = r.Scan(&i.Id, &i.InitialAmount, &i.Balance, &i.CreationDate.Time)
		if err != nil {
			return nil, err
		}

		i.Investor = *investor

		investements = append(investements, i)
	}

	return investements, nil
}

func (m InvestmentModel) RemoveBalance(id int) error {
	_, err := m.Db.Exec("UPDATE investments SET balance = 0 WHERE id = ?", id)

	return err
}
