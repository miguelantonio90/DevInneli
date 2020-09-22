import axios from 'axios'
import storage from './localStorage'
const baseUrl = process.env.MIX_APP_URL_API

const post = (resource, params) => {
    console.log(baseUrl);
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
