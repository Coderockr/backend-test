import  React,{useEffect,useState} from 'react';
import {Grid,Table,TableBody,TableCell,TableContainer,TableHead,TableRow,Paper, Button,TextField} from "@mui/material"

import {DatePicker,LocalizationProvider} from '@mui/lab';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';
import {useLocation,Link} from "react-router-dom";

function createData(dateInvest,valueInvest,withdrawn, id, dateWithdrawn) {
    return { dateInvest,valueInvest,withdrawn, id, dateWithdrawn };
}
function ListInvestiments(){
    var props = useLocation();
    var id = (props.state.id);
    var nome = (props.state.nome);
    const [rows,setRows] = useState([]);
    const [pgA,setPgA] = useState(1);

    useEffect(async()=>{
        var investiments = await fetch("http://localhost:8080/api/invest/list?owner="+id)
        var res = await investiments.json()
        res.map(x =>{
            setRows(prev=>[...prev,createData(x.dateInvest,x.valueInitalInvest,x.withdrawn,x.id,x.dateWithdrawn)])
        })
    },[])

    async function nextPage(){
        var valor = pgA + 1;
        setPgA(valor);
        setRows([]);
        var investiments = await fetch("http://localhost:8080/api/invest/list?owner="+id+"&pg="+valor)
        var res = await investiments.json()
        res.map(x =>{
            setRows(prev=>[...prev,createData(x.dateInvest,x.valueInitalInvest,x.withdrawn,x.id)])
        })
    }
    async function backPage(){
        var valor = pgA - 1;
        setPgA(valor);
        setRows([]);
        var investiments = await fetch("http://localhost:8080/api/invest/list?owner="+id+"&pg="+valor)
        var res = await investiments.json()
        res.map(x =>{
            setRows(prev=>[...prev,createData(x.dateInvest,x.valueInitalInvest,x.withdrawn,x.id)])
        })
    }

  return (
      <div class="home">
        <Button onClick={function voltar(){window.history.back()}}>voltar</Button>
        <Grid container spacing={2}>
             <Grid item xs={10}>
                <h3>Lista de Investimentos de {nome}</h3>
             </Grid>
             <Grid item xs={2}>
                <Link to={'/registerInvestiment'} state={ {nome:nome,id:id}} ><Button>Cadastrar Investimento</Button></Link>
             </Grid>
            <Grid item xs={12}>

                <TableContainer component={Paper} >
                <Table sx={{ minWidth: 650 }} size="small" aria-label="a dense table">
                    <TableHead>
                    <TableRow>
                        <TableCell>Data</TableCell>
                        <TableCell>Valor Investido</TableCell>
                        <TableCell>Retirado?</TableCell>
                        <TableCell align="right">Ações</TableCell>
                    </TableRow>
                    </TableHead>
                    <TableBody>
                    {rows.map((row) => (
                        <TableRow
                        key={row.dateInvest}
                        sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                        >
                        <TableCell component="th" scope="row">
                        <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                                <DatePicker
                                label={row.dateInvest}
                                disabled
                                value={row.dateInvest}
                                
                                renderInput={(params) => <TextField {...params} />}
                                />
                            </LocalizationProvider>
                            
                        </TableCell>
                        <TableCell component="th" scope="row">
                            {row.valueInvest}
                        </TableCell>
                        <TableCell component="th" scope="row">
                            {row.withdrawn ? <h1>SIM</h1>: <h1>NÃO</h1>}
                        </TableCell>
                        <TableCell align="right"><Link to={'/viewInvestiment'} state={ {row: row,nome:nome}} >Visualizar</Link></TableCell>                
                        
                    </TableRow>
                    ))}
                    </TableBody>
                </Table>
                </TableContainer>
             </Grid>
        
             <Grid item xs={10}>
                 {pgA > 1 &&

                    <Button variant="outlined" color="warning"  onClick={backPage}>Página Anterior</Button>
                 }
                 {rows.length == 5 &&
                    <Button variant="outlined" color="success"  onClick={nextPage}>Próxima Página</Button>
                 }

             </Grid>
        </Grid>
    </div>
  );
}

export default ListInvestiments