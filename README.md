## Teste para vaga Backend

[Documentação da API](https://documenter.getpostman.com/view/13842679/2s935mt5gL#ed53fb19-0db8-4043-8403-7a3923a63db2)

Para a realização do teste foi usado PHP com o framework Laravel, os testes foram feitos com PHPUnit e a documentação foi feita com o Postman

Existe um banco de dados onde todos os dados. no mesmo encontra-se uma entidade 
investidor (id_investidor, email), investimento (id_investimento, data, saldo_inicial, ganhos e "retirou ?")

pode-se criar varios investimentos para o mesmo investidor e um investimento pertence apenas a um investidor

no codigo tem um command, que é executado todos dias e verifica o dia e o mes se corresponde a data
que o investimento foi feito nessa data, caso sim, ele calcula os ganhos e adiciona os ganhos

tem um service que calcula as taxas sobre os ganhos e os ganhos
