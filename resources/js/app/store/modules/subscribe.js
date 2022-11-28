const actions = {
    getData({}, queryParams) {

      return new Promise((resolve, reject) => {
        axios.get(window.server_url + `/api/admin/subscribe/get_subscribe`, {
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
          axios.get(window.server_url + `/api/admin/subscribe/${id}`)
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
        axios.post(window.server_url + `/api/admin/subscribe`, data)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    startSubscribtion({}, id) {
      return new Promise((resolve, reject) => {
        axios.post(window.server_url + `/api/admin/subscribe/start_sub/${id}`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    giveSubscribtion({}, data) {
      return new Promise((resolve, reject) => {
        axios.post(window.server_url + `/api/admin/subscribe/give_sub/${data.id}`, data.formData)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },
    stopSubscribtion({}, data) {
      return new Promise((resolve, reject) => {
        axios.post(window.server_url + `/api/admin/subscribe/stop_sub/${data.id}`, data.formData)
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
        axios.post(window.server_url + `/api/admin/subscribe/${data.id}`, data)
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
        axios.put(window.server_url + `/api/admin/subscribe/`, JSON.parse(data))
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
          axios.delete(window.server_url + `/api/admin/subscribe`, {
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
        axios.post(window.server_url + `/api/admin/subscribe/upload_image/${data.id}`, form)
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
        axios.get(window.server_url + `/api/admin/subscribe/list_cuisines`)
          .then((response) => {
            resolve(response.data);
          })
          .catch((error) => {
            reject(error)
          })
      })
    },

    listBankNames({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/subscribe/list_bank_names`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listPeriods({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/subscribe/list_periods`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listCompanies({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/subscribe/list_companies`)
                .then((response) => {
                    resolve(response.data);
                })
                .catch((error) => {
                    reject(error)
                })
        })
    },

    listGroupNames({}) {
        return new Promise((resolve, reject) => {
            axios.get(window.server_url + `/api/admin/subscribe/list_group_names`)
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
        axios.get(window.server_url + `/api/admin/subscribe/list_ingredients`)
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
            axios.get(window.server_url + `/api/admin/subscribe/list_first_group_ingredients`)
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
            axios.get(window.server_url + `/api/admin/subscribe/list_second_group_ingredients`)
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
        axios.get(window.server_url + `/api/admin/subscribe/list_groups`)
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
        axios.get(window.server_url + `/api/admin/subscribe/list_tools`)
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
            axios.get(window.server_url + `/api/admin/subscribe/detail_packages/${id}`)
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
