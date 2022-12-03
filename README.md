Neste desafio deverás construir uma API para uma aplicação que armazena e gere investimentos, a mesma deverá ter as seguintes funcionalidades:

Criação de um investimento com proprietário, data de criação e valor.
A data de criação de um investimento pode ser hoje ou no passado.
Um investimento não deve ser ou se tornar negativo.
Visualização de um investimento com seu valor inicial e saldo esperado.
O saldo esperado deve ser a soma do valor investido e dos ganhos.
Se um investimento já foi retirado, o saldo deve refletir os ganhos desse investimento
Retirada de um investimento.
A retirada será sempre a soma do valor inicial e seus ganhos, a retirada parcial não é suportada.
As retiradas podem acontecer no passado ou hoje, mas não podem acontecer antes da criação do investimento ou no futuro.
Os impostos precisam ser aplicados aos saques antes de mostrar o valor final.
Lista de investimentos de uma pessoa
Esta lista deve ter paginação.
NOTA: a implementação de uma interface não será avaliada.

Cálculo de ganho
O investimento pagará 0,52% todo mês no mesmo dia da criação do investimento.

Tendo em vista que o ganho é pago mensalmente, ele deve ser tratado como ganho composto, ou seja, a cada novo período (mês) o valor ganho passará a fazer parte do saldo do investimento para o próximo pagamento.

Tributação
Quando o dinheiro é retirado, o imposto é acionado. Os impostos se aplicam apenas à parte do lucro/ganho do dinheiro sacado. Por exemplo, se o investimento inicial foi de 1.000,00, o saldo atual é de 1.200,00, então os impostos incidirão sobre os 200,00.

A porcentagem de imposto muda de acordo com a idade do investimento:

Se tiver menos de um ano, o percentual será de 22,5% (taxa = 45,00).
Se tiver entre um e dois anos, o percentual será de 18,5% (taxa = 37,00).
Se for maior de dois anos, o percentual será de 15% (taxa = 30,00).