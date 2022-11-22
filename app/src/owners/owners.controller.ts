import { OwnerEntity } from './entities/owner.entity';
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
import { ApiResponse, ApiTags } from '@nestjs/swagger';

@ApiTags('Owners')
@ApiResponse({
  status: 400,
  description: 'Bad Request',
})
@Controller('owners')
export class OwnersController {
  constructor(private readonly ownersService: OwnersService) {}

  @Post()
  @ApiResponse({
    status: 201,
    description: 'The record has been successfully created.',
    type: OwnerEntity,
  })
  create(@Body() createOwnerDto: CreateOwnerDto) {
    return this.ownersService.create(createOwnerDto);
  }

  @Get()
  @ApiResponse({
    status: 200,
    description: 'Request success',
    type: Array<OwnerEntity>,
  })
  findAll(@Query('take') take?: number, @Query('skip') skip?: number) {
    let skipValue = 0;
    let takeValue = 10;

    if (skip && skip > 0) skipValue = skip;
    if (take && take > 0) takeValue = take;

    return this.ownersService.findAll(skipValue, takeValue);
  }

  @Get(':id')
  @ApiResponse({
    status: 200,
    description: 'Request success',
    type: OwnerEntity,
  })
  findOne(
    @Param('id') id: string,
    @Query('take') take?: number,
    @Query('skip') skip?: number,
  ) {
    let skipValue = 0;
    let takeValue = 10;

    if (skip && skip > 0) skipValue = skip;
    if (take && take > 0) takeValue = take;

    return this.ownersService.findOne(+id, skipValue, takeValue);
  }

  @Patch(':id')
  @ApiResponse({
    status: 200,
    description: 'Request success',
    type: OwnerEntity,
  })
  update(@Param('id') id: string, @Body() updateOwnerDto: UpdateOwnerDto) {
    return this.ownersService.update(+id, updateOwnerDto);
  }

  @Delete(':id')
  @ApiResponse({
    status: 200,
    description: 'Request success',
    type: OwnerEntity,
  })
  @ApiResponse({
    status: 404,
    description: 'Not Found',
  })
  remove(@Param('id') id: string) {
    return this.ownersService.remove(+id);
  }
}
