import React, { useState } from "react"
import {Grid,Button, TextField} from "@mui/material"
import {useLocation,Link} from "react-router-dom";
import {MobileDatePicker,LocalizationProvider} from '@mui/lab';
import AdapterDateFns from '@mui/lab/AdapterDateFns';
import ptBR from 'date-fns/locale/pt-BR';


export default function RegisterInvestiment(){
    var props = useLocation();
    var id = (props.state.id);
    var nome = (props.state.nome);
    const [message,setMessage] = useState("")
    const [data, setData] = useState(new Date());
    const [valueInvestiment, setValueInvestiment] = useState(0);

    async function salvar(){
            if(valueInvestiment == ""){ 
                setMessage("O valor do investimento nao pode ser vazio") 
                return;
            }
            var x = await fetch("http://localhost:8080/api/invest",{ headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              method: "POST",
              body:JSON.stringify({
                owner:id,
                valueInitalInvest:valueInvestiment,
                dateInvest:data,
                withdrawn:false
              })
            })
            var res = await x.json();
            switch(x.status){
                case 400:
                    setMessage(res.message);
                    break;
                case 201:
                    setMessage("SUCESSO!");
                    window.history.back();
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
                <h3>Registrar o investimento de {nome}</h3>
            </Grid>
            <Grid item xs={12}>
                <TextField variant="outlined" onChange={e=>setValueInvestiment(e.target.value)} label="Insira o Valor a ser investido" type="number" fullWidth> </TextField>
            </Grid>
            <Grid item xs={12}>
                 <LocalizationProvider dateAdapter={AdapterDateFns} locale={ptBR}>
                    <MobileDatePicker
                        label="Insira a data do investimento"
                        value={data}
                        onChange={(newValue) => {
                            setData(newValue);
                        }}
                        renderInput={(params) => <TextField {...params} />}
                    />
                </LocalizationProvider>
            </Grid>
            <Grid item xs={12}>
                <Button variant="outlined" onClick={salvar} color="success">Confirmar</Button>
                <h3>{message}</h3>
            </Grid>
        </Grid>
        </div>
    )
}