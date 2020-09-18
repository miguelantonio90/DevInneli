import axios from 'axios'
import storage from './localStorage'

const baseUrl = process.env.VUE_APP_API_URL || 'http://localhost:8000/api/'

const post = (resource, params) => {
  return axios.post(baseUrl + resource, params, {
    headers: {
      Authorization: storage.getToken()
    }
  })
}
const get = (resource) => {
  return axios.get(baseUrl + resource, {
    headers: {
      Authorization: storage.getToken()
    }
  })
}
const put = (resource, params) => {
  return axios.put(baseUrl + resource, params, {
    headers: {
      Authorization: storage.getToken()
    }
  })
}

const remove = (resource) => {
  return axios.delete(baseUrl + resource, {
    headers: {
      Authorization: storage.getToken()
    }
  })
}

export default {
  get,
  post,
  put,
  remove
}
