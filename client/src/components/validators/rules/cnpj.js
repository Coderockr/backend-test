function validate (cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g, '')
  
    let pesosDigito1 = [ 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ]
    let pesosDigito2 = [ 6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2 ]
  
    return verificacaoGeral(cnpj) && verificarDigito(cnpj, pesosDigito1) && verificarDigito(cnpj, pesosDigito2)
  }
  
  function verificacaoGeral (cnpj) {
    let excludeArray = [
      '00000000000000',
      '11111111111111',
      '22222222222222',
      '33333333333333',
      '44444444444444',
      '55555555555555',
      '66666666666666',
      '77777777777777',
      '88888888888888',
      '99999999999999'
    ]
  
    if (cnpj === '') return false
    if (cnpj.length !== 14) return false
    if (excludeArray.some(o => cnpj === o)) return false
  
    return true
  }
  
  function verificarDigito (cnpj, pesos) {
    let numbers = cnpj.split('').slice(0, pesos.length)
    // Soma numeros do CNPJ baseado nos pesos
    let acumuladora = numbers.reduce((anterior, atual, index) => {
      return anterior + (atual * pesos[index])
    }, 0)
    let resto = acumuladora % 11
    let digito = resto < 2 ? 0 : 11 - resto
    return parseInt(cnpj[pesos.length]) === digito
  }
  
  export default validate