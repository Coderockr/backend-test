package models

import (
	"encoding/json"
	"strings"
	"time"
)

type Date struct{ time.Time }

func (t Date) MarshalJSON() ([]byte, error) {
	return json.Marshal(t.Time)
}

func (t *Date) UnmarshalJSON(b []byte) error {
	err := json.Unmarshal(b, &t.Time)
	if err != nil {
		bstr := strings.Trim(string(b), `"`)
		t.Time, err = time.Parse("2006-01-02", bstr)
		if err != nil {
			return err
		}
	}
	return nil
}
