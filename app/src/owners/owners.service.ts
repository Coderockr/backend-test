import { NotFoundException } from '@nestjs/common/exceptions';
import { OwnersRepository } from './repositories/owners.repository';
import { Injectable } from '@nestjs/common';
import { CreateOwnerDto } from './dto/create-owner.dto';
import { UpdateOwnerDto } from './dto/update-owner.dto';

@Injectable()
export class OwnersService {
  constructor(private readonly ownersRepository: OwnersRepository) {}
  create(createOwnerDto: CreateOwnerDto) {
    return this.ownersRepository.create(createOwnerDto);
  }

  findAll(skip: number, take: number) {
    return this.ownersRepository.findAll(skip, take);
  }

  findOne(id: number, skip = 0, take = 10) {
    return this.ownersRepository.findOne(id, skip, take);
  }

  update(id: number, updateOwnerDto: UpdateOwnerDto) {
    const owner = this.ownersRepository.findOne(id);

    if (!owner) {
      throw new NotFoundException('Owner not found!');
    }

    return this.ownersRepository.update(id, updateOwnerDto);
  }

  remove(id: number) {
    const owner = this.ownersRepository.findOne(id);

    if (!owner) {
      throw new NotFoundException('Owner not found!');
    }

    return this.ownersRepository.remove(id);
  }
}
