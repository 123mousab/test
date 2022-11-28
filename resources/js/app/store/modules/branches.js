const actions = {
  getData({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.get(window.server_url + `/api/admin/branches`, {
        params: queryParams
      })
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  findData({}, id) {
    return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/branches/${id}`)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  saveData({}, data) {
      console.log(data)
    return new Promise((resolve, reject) => {
      axios.post(window.server_url + `/api/admin/branches`, data)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  updateData({}, data) {
    return new Promise((resolve, reject) => {
      axios.post(window.server_url + `/api/admin/branches/${data.id}`, data)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  updateStatus({}, data) {
    return new Promise((resolve, reject) => {
      axios.put(window.server_url + `/api/admin/branches`, JSON.parse(data))
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

    updateImage({}, data) {
        return new Promise((resolve, reject) => {
            let form = new FormData();
            if( data.image) form.append('image', data.image);
            axios.post(window.server_url + `/api/admin/branches/upload_image/${data.id}`, form)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listCities({}, cityId) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/branches/list_cities/${cityId}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listAllCities({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/branches/list_all_cities`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listBranches({}, id) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/branches/list_branches/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },


  removeData({}, id) {
    return new Promise((resolve, reject) => {
        axios.delete(window.server_url + `/api/admin/branches`, {
            data: {
                ids: id
            }
        })
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  }

}
export default {
  namespaced: true,
  actions,
}
