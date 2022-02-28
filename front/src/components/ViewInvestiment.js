import  React,{useEffect,useState} from 'react';
import {Grid, Button,TextField} from "@mui/material"


import {DatePicker,LocalizationProvider} from '@mui/lab';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';
import {useLocation,Link} from "react-router-dom";

function ViewInvestiment(){
    var props = useLocation();
    var nome = (props.state.nome);
    var row = (props.state.row);
    const [ret,setRet] = useState([]);

    useEffect(async()=>{
        var investiments = await fetch("http://localhost:8080/api/invest/"+row.id+"?dataWithdrawn="+row.dateWithdrawn)
        var res = await investiments.json()
        setRet(res);
    },[])

    

  return (
      <div class="home">
          <Button onClick={function voltar(){window.history.back()}}>voltar</Button>
        <Grid container spacing={2}>
             <Grid item xs={10}>
                <h3>Visualizar Investimento de {nome}</h3>
             </Grid>
             <Grid item xs={2}>
            {row.withdrawn ?
                <h3>Já retirado!</h3>  
             :
                <Link to={'/withdrawalInvestiment'} state={ {nome:nome,row:row,ret:ret}} ><Button>Retirar Investimento</Button></Link>
            }
             </Grid>
             <Grid item xs={12}>
                <h3>Valor de Investimento Inicial: R$ {row.valueInvest}</h3>
                <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                    <h3>Data do Investimento:</h3>
                    <DatePicker
                        label={row.dateInvest}
                        disabled
                        value={row.dateInvest}
                        
                        renderInput={(params) => <TextField {...params} />}
                    />
                </LocalizationProvider>
             </Grid>
             <Grid item xs={12}>
                 <h3>Meses fechados: {ret.total}</h3>
            
                 <h3>Rendimento Bruto: {ret.lucroBruto}</h3>
           
                 <h3>Imposto a ser aplicado: {ret.imposto}</h3>
             </Grid>
             <Grid item xs={12}>
                 {row.withdrawn ?
                        <>
                        <h3>Valor Retirado: R$ {ret.result}</h3>
                        <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                        <h3>Data da retirada:</h3>
                        <DatePicker
                            label={row.dateWithdrawn}
                            disabled
                            value={row.dateWithdrawn}
                            
                            renderInput={(params) => <TextField {...params} />}
                        />
                    </LocalizationProvider>
                    </>
                 :
                    <>  
                        <h3>Valor atual com rentabilidade: R$ {ret.result}</h3>
                        <small>Valor caso a retirada fosse hoje já com a dedução de impostos.</small>

                    </>

                 }
             </Grid>
             
        </Grid>

    </div>
  );
}

export default ViewInvestiment