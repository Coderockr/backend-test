import  React,{useEffect,useState} from 'react';
import {Grid,Table,TableBody,TableCell,TableContainer,TableHead,TableRow,Paper, Button} from "@mui/material"
import "./Home.css"
import {Link} from "react-router-dom"

function createData(name, id) {
  return { name, id };
}

export default function DenseTable() {
    const [rows,setRows] = useState([]);
    useEffect(async()=>{
        var owners = await fetch("http://localhost:8080/api/owner/list")
        var res = await owners.json()
        res.map(x =>{
            setRows(prev=>[...prev,createData(x.owner_name,x.id)])
        })
    },[])


  return (
      <div class="home">
        <Grid container spacing={2}>
             <Grid item xs={10}>
                <h3>Lista de Investidores cadastrados</h3>
             </Grid>
             <Grid item xs={2}>
                <Button href="/registerOwner">Cadastrar Investidor</Button>
             </Grid>
        </Grid>

        <TableContainer component={Paper} >
        <Table sx={{ minWidth: 650 }} size="small" aria-label="a dense table">
            <TableHead>
            <TableRow>
                <TableCell>Nome</TableCell>
                <TableCell align="right">Ações</TableCell>
            </TableRow>
            </TableHead>
            <TableBody>
            {rows.map((row) => (
                <TableRow
                key={row.name}
                sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                >
                <TableCell component="th" scope="row">
                    {row.name}
                </TableCell>
                <TableCell align="right"><Link to={'/listInvestiments'} state={ {nome:row.name,id: row.id}} >Ver Investimentos</Link></TableCell>
                </TableRow>
            ))}
            </TableBody>
        </Table>
        </TableContainer>
    </div>
  );
}