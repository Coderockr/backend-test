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
	}

	http.HandleFunc("/api/investors", investorH.CreateInvestor)
	http.HandleFunc("/api/investors/{cpf}", investorH.FindInvestorByCpf)

	http.HandleFunc("/api/investments", investmentH.CreateInvestment)
	http.HandleFunc("/api/investments/{id}", investmentH.FindInvestmentById)

	http.HandleFunc("/api/witdrawals", withdrawalH.CreateWithdrawal)
	http.HandleFunc("/api/witdrawals/{id}", withdrawalH.FindWithdrawalById)

	http.ListenAndServe(":8080", nil)
}
