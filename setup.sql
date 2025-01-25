drop database if exists investments;
create database investments;
use investments;

create table investors (
    cpf varchar(11) not null,
    name text not null,

    primary key (cpf)
);

create table investments (
    id int auto_increment,
    initial_amount int not null,
    balance int not null,
    creation_date date not null,
    investor_cpf varchar(11),
    
    primary key (id),
    foreign key (investor_cpf) references investors(cpf) 
);
