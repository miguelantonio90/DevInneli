// import api from '@/config/api'
import api from '../config/api'

/** Auth API */
const getUserData = () => {
  return api.get('auth')
}

const loginRequest = ({ email, password }) => {
  return api.post('login', { email, password })
}

const loginPincodeRequest = ({ email, pincode }) => {
  const user = {
    email: email,
    pincode: pincode
  }
  return api.post('login/pincode', user)
}

const registerRequest = (user) => {
  return api.post('register', user)
}

const logoutRequest = () => {
  return api.post('logout')
}

const verifyResendRequest = () => {
  return api.get('email/resend')
}

const verifyRequest = (hash) => {
  return api.get(`email/verify/${hash}`)
}

const verifyMailForgot = (email) => {
  return api.post('password/reset/link', { email: email })
}

const resetPassword = (hash, newData) => {
  return api.post('password/reset/new/' + hash, newData)
}

const readNotification = (idNotification) => {
  return api.post('user/read/notification/' + idNotification)
}

export default {
  getUserData,
  loginRequest,
  loginPincodeRequest,
  logoutRequest,
  registerRequest,
  verifyRequest,
  verifyResendRequest,
  verifyMailForgot,
  resetPassword,
  readNotification
}
