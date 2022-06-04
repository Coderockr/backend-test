export const makeInvestmentQuery = (data: {
  userId: string
  customDate?: Date
  initialValue?: number
}) => `mutation {
    createInvestment(data: { 
    initialAmount: ${data.initialValue ?? 100}, 
    creationDate: "${data.customDate ?? new Date()}", 
    user_id: "${data?.userId}"
}) {
      id,
      initialAmount, 
      creationDate, 
      user_id 
}}`

export const makeUserQuery = (data?: {
  bornDate?: Date
  email?: string
}) => `mutation {
    createUser(data: {
      name: "Test User ${new Date().getDate()}"
      bornDate: "${data?.bornDate ?? new Date()}",
      email: "${data?.email ?? `testuser${new Date().getTime()}@gmail.com`}"
    }) {
      id
      name,
      email,
      bornDate
    }
  }`

export const deleteUserQuery = (userId: string) => `mutation {
  deleteUser(id: "${userId}") {
    id
    name,
    email,
    bornDate
  }
}`

export const getInvestmentQuery = (investmentId: string) => `query {
    getInvestmentByid(id: "${investmentId}") {
        id,
        initialAmount, 
        creationDate, 
        user_id,
        currentAmount,
    		gainAmount,
    }
  }`

export const getInvestmentsByUserQuery = (userId: string) => `query {
    returnAllInvestmentsByUser(user_id: "${userId}") {
        id,
        initialAmount,
        creationDate,
        user_id
    }
}`

export const deleteInvestmentQuery = (investmentId: string) => `mutation {
    deleteInvestment(id: "${investmentId}") {
      id,
      initialAmount,
      creationDate,
      user_id
  } 
}`

export const withdrawalInvestment = (data: {
  investmentId: string
  creationDate?: Date
}) => `mutation {
  withdrawalInvestment(data: { 
  investment_id: "${data?.investmentId}", 
  creationDate: "${data?.creationDate ?? new Date()}", 
}) {
   id,
   creationDate,
   tax,
   finalValue,
   investment_id 
}}`

export const getWithdrawalByInvestment = (investmentId: string) => `query {
  getWithdrawalByInvestment(id: "${investmentId}") {
    id,
    creationDate,
    tax,
    finalValue,
    investment_id 
  }
}`
