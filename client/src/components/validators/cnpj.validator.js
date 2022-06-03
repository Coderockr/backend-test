import CnpjValidate from './rules/cnpj'
const validator = {
  getMessage () { // will be added to default English messages.
    return 'Invalid CNPJ'
  },
  validate (value) {
    return CnpjValidate(value)
  }
}
export default validator