const actions = {
    getData({}, queryParams) {

      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/menus`, {
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
          axios.get(window.server_url + `/api/admin/menus/${id}`)
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
        axios.post(window.server_url + `/api/admin/menus`, data)
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
        axios.post(window.server_url + `/api/admin/menus/${data.id}`, data)
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
        axios.put(window.server_url + `/api/admin/menus/`, JSON.parse(data))
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
          axios.delete(window.server_url + `/api/admin/menus`, {
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

    listRecipies({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/menus/list_recipies`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listFirstGroupIngredients({}) {
      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/menus/list_first_group_ingredients`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listSecondGroupIngredients({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/menus/list_second_group_ingredients`)
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
