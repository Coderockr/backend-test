import {
  Controller,
  Get,
  Post,
  Body,
  Patch,
  Param,
  Delete,
  Query,
} from '@nestjs/common';
import { OwnersService } from './owners.service';
import { CreateOwnerDto } from './dto/create-owner.dto';
import { UpdateOwnerDto } from './dto/update-owner.dto';
import { ApiTags } from '@nestjs/swagger';
import { query } from 'express';

@ApiTags('Owners')
@Controller('owners')
export class OwnersController {
  constructor(private readonly ownersService: OwnersService) {}

  @Post()
  create(@Body() createOwnerDto: CreateOwnerDto) {
    return this.ownersService.create(createOwnerDto);
  }

  @Get()
  findAll(@Query('take') take: number, @Query('skip') skip: number) {
    let skipValue = 0;
    let takeValue = 10;

    if (skip && skip > 0) skipValue = skip;
    if (take && take > 0) takeValue = take;

    return this.ownersService.findAll(skipValue, takeValue);
  }

  @Get(':id')
  findOne(
    @Param('id') id: string,
    @Query('take') take: number,
    @Query('skip') skip: number,
  ) {
    let skipValue = 0;
    let takeValue = 10;

    if (skip && skip > 0) skipValue = skip;
    if (take && take > 0) takeValue = take;

    return this.ownersService.findOne(+id, skipValue, takeValue);
  }

  @Patch(':id')
  update(@Param('id') id: string, @Body() updateOwnerDto: UpdateOwnerDto) {
    return this.ownersService.update(+id, updateOwnerDto);
  }

  @Delete(':id')
  remove(@Param('id') id: string) {
    return this.ownersService.remove(+id);
  }
}
