package models

type Investor struct {
	Cpf  string `json:"cpf" validate:"required,cpf"`
	Name string `json:"name" validate:"required"`
}

type InvestorModel struct {
	Db database
}

func (m InvestorModel) Create(i Investor) error {
	_, err := m.Db.Exec("INSERT INTO investors (cpf, name) VALUES (?, ?)", i.Cpf, i.Name)
	if err != nil {
		return err
	}

	return nil
}

func (m InvestorModel) ByCpf(cpf string) (*Investor, error) {
	r := m.Db.QueryRow("SELECT cpf, name FROM investors where cpf = ?", cpf)

	var i Investor

	err := r.Scan(&i.Cpf, &i.Name)
	if err != nil {
		return nil, err
	}

	return &i, nil
}
