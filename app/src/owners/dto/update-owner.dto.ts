import { PartialType } from '@nestjs/swagger';
import { CreateOwnerDto } from './create-owner.dto';

export class UpdateOwnerDto extends PartialType(CreateOwnerDto) {}
