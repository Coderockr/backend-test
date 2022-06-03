import axios from 'axios';

const instance = axios.create({
    baseURL: process.env.VUE_APP_API_URL
});
instance.CancelToken = axios.CancelToken
instance.isCancel = axios.isCancel

export default instance
