import CpfValidate from './rules/cpf'
const validator = {
  getMessage () { // will be added to default English messages.
    return 'Invalid CPF'
  },
  validate (value) {
    return CpfValidate(value)
  }
}
export default validator