export class CreateInvestmentResponseDto {
  id?: number;
  owner_id: number;
  creation_date: Date;
  amount: number;
  expected_balance: number;
  initial_amount: number;
}
