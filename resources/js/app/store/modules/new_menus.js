const actions = {
    getData({}, queryParams) {

      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/new_menus`, {
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
          axios.get(window.server_url + `/api/admin/new_menus/${id}`)
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
        axios.post(window.server_url + `/api/admin/new_menus`, data)
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
        axios.post(window.server_url + `/api/admin/new_menus/${data.id}`, data)
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
        axios.put(window.server_url + `/api/admin/new_menus/`, JSON.parse(data))
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
          axios.delete(window.server_url + `/api/admin/new_menus`, {
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

    listGroups({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_groups`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listRecipiesOfGroup({}, id) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_recipies_of_group/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listRecipiesOfGroup1({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_recipies_of_group1`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },


    listMainIngredients({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_main_ingredients`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listProteinRecipies({}, id) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_protein_recipies/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listProteinRecipies1({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_protein_recipies1`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listCarbRecipies({}, id) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_carb_recipies/${id}`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listCarbRecipies1({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/new_menus/list_carb_recipies1`)
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
