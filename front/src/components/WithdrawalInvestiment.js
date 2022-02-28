import React, { useState } from "react"
import {Grid,Button, TextField} from "@mui/material"
import {useLocation,Link} from "react-router-dom";
import {MobileDatePicker,LocalizationProvider} from '@mui/lab';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';


export default function WithdrawalInvestiment(){
    var props = useLocation();
    var row = (props.state.row);
    var retProp = (props.state.ret);
    var nome = (props.state.nome);

    const [message,setMessage] = useState("")
    const [data, setData] = useState(new Date());
    const [ret,setRet] = useState(retProp);
    const [permitirRetirada,setPermitirRetirada] = useState(true);

    async function atualizaValores(){
        var investiments = await fetch("http://localhost:8080/api/invest/"+row.id+"?dataWithdrawn="+data)
        
        var res = await investiments.json()
        setRet(res);
        switch(investiments.status){
            case 400:
                setMessage(res.message);
                setPermitirRetirada(false);
            break;
            case 201:
                setMessage("");
                setPermitirRetirada(true);
                // window.history.back();
            break;
           
        }
    }

    async function salvar(){
            
            var x = await fetch("http://localhost:8080/api/invest/withdrawal",{
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              method:"POST",
              body:JSON.stringify({
                id_invest:row.id,
                dataWithdrawn:data,
                valueWithdrawn:ret.result,
                dateInvest:row.dateInvest
              })
            })
            console.log(x);
            var res = await x.json();
            console.log(res)
            switch(x.status){
                case 400:
                    setMessage(res.message);
                    break;
                case 201:
                    setMessage("SUCESSO!");
                    window.location.href="/"
                    break;
                case 500:
                    setMessage(res.message);
                    break;
            }
    }

    return(    
        <div class="home">
            <Button onClick={function voltar(){window.history.back()}}>voltar</Button>
        <Grid container spacing={2}>
            <Grid item xs={12}>
                <h3>Retirar o investimento de {nome}</h3>
            </Grid>
            <Grid item xs={12}>
                <h3>Valor de Investimento Inicial: R$ {row.valueInvest}</h3>                    <small>Selecione a data de retirada do investimento</small><br/><br/>
                <Grid item xs={12}>
                    <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                        <MobileDatePicker
                            label="Insira a data da retirada do Investimento"
                            value={data}
                            onChange={(newValue) => {
                                setData(newValue);
                                atualizaValores();
                            }}
                            renderInput={(params) => <TextField {...params} />}
                        />
                    </LocalizationProvider>
                </Grid>
                 <h3>Mêses fechados: {ret.total}</h3>
            
                 <h3>Rendimento Bruto: {ret.lucroBruto}</h3>
           
                 <h3>Imposto a ser aplicado: {ret.imposto}</h3>
             </Grid>
             <Grid item xs={12}>
                 <h3>Valor atual com rentabilidade: R$ {ret.result}</h3>
                 <small>Valor já com a dedução de impostos.</small>
             </Grid>
            <Grid item xs={12}>
                { permitirRetirada &&
                    <Button variant="outlined" onClick={salvar} color="success">Confirmar</Button>

                }
                <h3>{message}</h3>
            </Grid>
        </Grid>
        </div>
    )
}