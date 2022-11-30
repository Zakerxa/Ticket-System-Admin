import authorize from '../../authorize';

export default {
    actions: {
        logout({ commit, state, rootState }) {
            return new Promise((resolve, reject) => {
                authorize('api/logout').then(res => {
                    if (res.data.response == 'logout') {
                        commit('removeAuthorize', '', { root: true })
                        resolve();
                    } else reject('Unauthorize');
                })
            })
        }
    }
}