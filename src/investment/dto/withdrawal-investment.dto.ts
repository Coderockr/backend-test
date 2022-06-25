import { IsDateString, IsNotEmpty, IsNumber, IsPositive, IsString } from "class-validator";

export class WithdrawalInvestmentDto {
    
    @IsString()
    @IsNotEmpty()
    id: string;

    @IsDateString()
    @IsNotEmpty()
    date: Date;      
}
