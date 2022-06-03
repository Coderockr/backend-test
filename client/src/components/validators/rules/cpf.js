function calcChecker1 (firstNineDigits) {
    let sum = null
  
    for (let j = 0; j < 9; ++j) {
      sum += firstNineDigits.toString().charAt(j) * (10 - j)
    }
  
    const lastSumChecker1 = sum % 11
    const checker1 = (lastSumChecker1 < 2) ? 0 : 11 - lastSumChecker1
  
    return checker1
  }
  
  function calcChecker2 (cpfWithChecker1) {
    let sum = null
  
    for (let k = 0; k < 10; ++k) {
      sum += cpfWithChecker1.toString().charAt(k) * (11 - k)
    }
    const lastSumChecker2 = sum % 11
    const checker2 = (lastSumChecker2 < 2) ? 0 : 11 - lastSumChecker2
  
    return checker2
  }
  
  function cleaner (value) {
    const digits = value.replace(/[^\d]/g, '')
    return digits
  }
  
  function validate (value) {
    const cleanCPF = cleaner(value)
    const firstNineDigits = cleanCPF.substring(0, 9)
    const checker = cleanCPF.substring(9, 11)
    let result = false
  
    for (let i = 0; i < 10; i++) {
      if (firstNineDigits + checker === Array(12).join(i)) {
        return false
      }
    }
  
    const checker1 = calcChecker1(firstNineDigits)
    const checker2 = calcChecker2(`${firstNineDigits}${checker1}`)
  
    if (checker.toString() === checker1.toString() + checker2.toString()) {
      result = true
    } else {
      result = false
    }
    return result
  }
  export default validate