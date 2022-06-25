import { IsNotEmpty, IsNumberString, IsOptional, IsString } from 'class-validator';

export class ListParamsDto {
    @IsOptional()
    @IsNumberString()
    offset?: number;

    @IsOptional()
    @IsNumberString()
    limit?: number;

    @IsNotEmpty()
    @IsString()
    owner?: string;
}