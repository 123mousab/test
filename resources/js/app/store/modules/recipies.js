const actions = {
    getData({}, queryParams) {

      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/recipies`, {
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
          axios.get(window.server_url + `/api/admin/recipies/${id}`)
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
        axios.post(window.server_url + `/api/admin/recipies`, data)
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
        axios.post(window.server_url + `/api/admin/recipies/${data.id}`, data)
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
        axios.put(window.server_url + `/api/admin/recipies/`, JSON.parse(data))
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
          axios.delete(window.server_url + `/api/admin/recipies`, {
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
        axios.post(window.server_url + `/api/admin/recipies/upload_image/${data.id}`, form)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listCuisines({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/recipies/list_cuisines`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listIngredients({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/recipies/list_ingredients`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listPrimaryIngredients({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/recipies/list_primary_ingredients`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listGroups({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/recipies/list_groups`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },


    listTools({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/recipies/list_tools`)
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
