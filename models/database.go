package models

import "database/sql"

type Database interface {
	Query(query string, args ...interface{}) (*sql.Rows, error)

	QueryRow(query string, args ...interface{}) *sql.Row

	Exec(query string, args ...interface{}) (sql.Result, error)
}
