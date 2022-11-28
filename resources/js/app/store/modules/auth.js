const actions = {
  login({}, data) {
    return new Promise((resolve, reject) => {
       let form = new FormData();
       if( data.email) form.append('email', data.email);
       if( data.password) form.append('password', data.password);
      axios.post(window.server_url + `/api/auth/admin/login`, form)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
  logout({}) {
    return new Promise((resolve, reject) => {
      axios.post(window.server_url + `/api/auth/admin/logout`)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  login2({}, data) {
    return new Promise((resolve, reject) => {
       let form = new FormData();
       if( data.email) form.append('email', data.email);
       if( data.password) form.append('password', data.password);
      axios.post(window.server_url + `/api/admin/login`, form)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
  logout2({}) {
    return new Promise((resolve, reject) => {
      axios.post(window.server_url + `/api/admin/logout`)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

}
export default {
  namespaced: true,
  actions,
}
