package main

import (
	"database/sql"
	"log"
	"net/http"

	"causeurgnocchi/backend-test/handlers"
	"causeurgnocchi/backend-test/models"
)

func main() {
	db, err := sql.Open("mysql", "root:example@(127.0.0.1:3306)/investments?parseTime=true")
	if err != nil {
		log.Fatal(err)
	}
	defer db.Close()

	investorH := handlers.InvestorHandler{
		Investors: models.InvestorModel{Db: db},
	}

	investmentH := handlers.InvestmentHandler{
		Investments: models.InvestmentModel{Db: db},
	}

	withdrawalH := handlers.WithdrawalHandler{
		Withdrawals: models.WithdrawalModel{Db: db},
		Investments: models.InvestmentModel{Db: db},
	}

	http.HandleFunc("GET /api/investors/{cpf}", investorH.FindInvestorByCpf)
	http.HandleFunc("POST /api/investors", investorH.CreateInvestor)

	http.HandleFunc("GET /api/investments", investmentH.FilterByInvestorCpf)
	http.HandleFunc("GET /api/investments/{id}", investmentH.FindInvestmentById)
	http.HandleFunc("POST /api/investments", investmentH.CreateInvestment)

	http.HandleFunc("GET /api/withdrawals/{id}", withdrawalH.FindWithdrawalById)
	http.HandleFunc("POST /api/withdrawals", withdrawalH.CreateWithdrawal)

	http.ListenAndServe(":8080", nil)
}
