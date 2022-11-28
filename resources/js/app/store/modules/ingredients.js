const actions = {
    getData({}, queryParams) {

      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/ingredients`, {
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
          axios.get(window.server_url + `/api/admin/ingredients/${id}`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    saveData({}, data) {
      return new Promise((resolve, reject) => {
        axios.post(window.server_url + `/api/admin/ingredients`, data)
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
        axios.post(window.server_url + `/api/admin/ingredients/${data.id}`, data)
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
        axios.put(window.server_url + `/api/admin/ingredients/`, JSON.parse(data))
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
          axios.delete(window.server_url + `/api/admin/ingredients`, {
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
    },

    updateImage({}, data) {
      return new Promise((resolve, reject) => {
         let form = new FormData();
         if( data.image) form.append('image', data.image);
        axios.post(window.server_url + `/api/admin/ingredients/upload_image/${data.id}`, form)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listUnits({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/ingredients/list_units`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listNutrionFacts({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/ingredients/list_nutrtion_facts`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listDivisions({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/ingredients/list_divisions`)
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
