import { IsDateString, IsNotEmpty, IsNumber, IsPositive, IsString } from "class-validator";

export class CreateInvestmentDto {
    
    @IsString()
    @IsNotEmpty()
    owner: string;

    @IsPositive()
    @IsNumber()
    @IsNotEmpty()
    amount: number;    

    @IsDateString()
    @IsNotEmpty()
    date: Date;      
}
