const actions = {
  getCookingTodayReport({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.post(window.server_url + `/api/admin/subscribe/table_cooking_date`, queryParams)
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
  getQuantititesReport({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.get(window.server_url + `/api/admin/subscribe/report_quantities`, {params: queryParams})
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },
  getDelegateReport({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.get(window.server_url + `/api/admin/subscribe/report_deliveries`, {params: queryParams})
        .then((response) => {
          resolve(response.data);
        })
        .catch((error) => {
          reject(error)
        })
    })
  },

  downloadCookingTodayPDFReport({}, queryParams) {

    return new Promise((resolve, reject) => {
      axios.get(window.server_url + `/api/admin/subscribe/generate_cooking_today`)
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
