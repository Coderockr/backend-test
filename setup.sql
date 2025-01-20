drop database if exists investments;
create database investments;
use investments;

create table investments (
    id int auto_increment,
    amount decimal(15, 2) not null,
    creation_date date not null,
    owner_id int,
    primary key (id),
    foreign key (owner_id) references owner(id) 
);

create table owners {
    id int auto_increment,
    name text not null,
    cpf text not null,
    primary key (id)
}
