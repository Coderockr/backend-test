import React, { useState } from "react"
import {Grid,Button, TextField} from "@mui/material"

export default function RegisterOwner(){
    let ownerName = ""
    const[message,setMessage] = useState("")
    async function salvar(){
            if(ownerName == ""){ 
                setMessage("O nome do Investidor nao pode ser vazio") 
                return;
            }
            var x = await fetch("http://localhost:8080/api/owner",{ headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
              },
              method: "POST",
              body:JSON.stringify({
                owner_name:ownerName
              })
            })
            window.location.href="/";
    }

    return(    
        <div class="home">
            <Button onClick={function voltar(){window.history.back()}}>voltar</Button>
        <Grid container spacing={2}>
            <Grid item xs={12}>
                <h3>Insira o nome do novo investidor</h3>
            </Grid>
            <Grid item xs={12}>
                <TextField variant="outlined" onChange={e=>ownerName = e.target.value} fullWidth> </TextField>
            </Grid>
            <Grid item xs={12}>
                <Button variant="outlined" onClick={salvar} color="success">Confirmar</Button>
                <h3>{message}</h3>
            </Grid>
        </Grid>
        </div>
    )
}