import authorize from '../../authorize';
export default {
    actions: {
        delTicket({ commit, dispatch }, id) {
            return new Promise((resolve, reject) => {
                authorize.post(`api/user/delete/ticket/${id}`)
                    .then(res => {
                        if (res.data.response == 'success') resolve(dispatch('getMyTicket'))
                        else reject();
                    })
                    .catch(err => reject(err))
            })
        },
        getMyTicket({ state, commit, rootState }) {
            return new Promise((resolve, reject) => {
                authorize('api/user/tickets')
                    .then(res => {
                        if (res.data.response == 'success') resolve(rootState.userTickets = res.data.tickets);
                    })
                    .catch(err => reject(err))
            })
        },
        editTicket({ state, commit, dispatch }, form) {
            return new Promise((resolve, reject) => {
                authorize.post(`api/user/edit/ticket/${form.id}`, { code: form.code })
                    .then(res => {
                        if (res.data.response == 'success') resolve(dispatch('getMyTicket'))
                        else reject();
                    })
                    .catch(err => {
                        console.log("Error in editTicket");
                    })
            })
        }
    }
}