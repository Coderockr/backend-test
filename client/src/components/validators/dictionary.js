// modelo edit mensagens vee-validate
const dictionary = {
    pt: {
      messages: {
        required: `O campo é obrigatório.`,
        email: `O campo deve ser um e-mail válido`,
        min: (field,length) => `O campo deve ter pelo menos ${length} caracteres`,
        cpf: `O campo deve ser um cpf válido`,
        cnpj: `O campo deve ser um cnpj válido`,
        date_format: `O campo não é válido`,
        regex: `O campo não é válido`,
        confirmed:(field,field2) => `Os campos ${field} e ${field2} devem ser iguais`
      }
    }
  }
export default dictionary