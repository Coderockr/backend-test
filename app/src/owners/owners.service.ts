import { OwnerRepository } from './repositories/owners.repository';
import { Injectable } from '@nestjs/common';
import { CreateOwnerDto } from './dto/create-owner.dto';
import { UpdateOwnerDto } from './dto/update-owner.dto';

@Injectable()
export class OwnersService {
  constructor(private readonly ownerRepository: OwnerRepository) {}
  create(createOwnerDto: CreateOwnerDto) {
    return this.ownerRepository.create(createOwnerDto);
  }

  findAll(skip: number, take: number) {
    return this.ownerRepository.findAll(skip, take);
  }

  findOne(id: number, skip: number, take: number) {
    return this.ownerRepository.findOne(id, skip, take);
  }

  update(id: number, updateOwnerDto: UpdateOwnerDto) {
    return `This action updates a #${id} owner`;
  }

  remove(id: number) {
    return `This action removes a #${id} owner`;
  }
}
