const actions = {
  getData({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.get(window.server_url + `/api/admin/packages`, {
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
        axios.get(window.server_url + `/api/admin/packages/${id}`)
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
      axios.post(window.server_url + `/api/admin/packages`, data)
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
      axios.post(window.server_url + `/api/admin/packages/${data.id}`, data)
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
      axios.put(window.server_url + `/api/admin/packages`, JSON.parse(data))
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
            axios.post(window.server_url + `/api/admin/packages/upload_image/${data.id}`, form)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listPackages({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/packages/list_packages`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    packageDetails({}, id) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/packages/detail_packages/${id}`)
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
        axios.delete(window.server_url + `/api/admin/packages`, {
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
