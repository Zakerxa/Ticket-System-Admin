<template>

    <div v-if="loading" class="loading">
       <!-- Show Loading if Data not ready yet -->
       <div class="row d-flex align-items-center p-0 m-0" style="height:100vh;width:100vw;">
           <div class="col-12 text-center p-0">
               <h3 class="fw-bold">{{loadingIcon}}</h3>
           </div>
       </div>
    </div>
    <div v-else>
        <router-view/>
    </div>


</template>

<script>
import {
    mapGetters
} from 'vuex';
export default {
    data(){
        return{
            loadingIcon : '<Loading/>',
            loading : true
        }
    },
    computed: { ...mapGetters(['auth', 'authUser'])},
    watch: {
        $route(to, from) {
            this.$store.dispatch('gettingAuthUser').then(() => {
                if (!this.authUser) this.$store.commit('removeAuthorize');
            }).catch(() => {
                if (!this.authUser) this.$store.commit('removeAuthorize');
            })
            setTimeout(() => this.$store.commit('globalMessage', null), 2000);
        }
    },
    mounted(){
       document.addEventListener('DOMContentLoaded', () => setTimeout(() => this.loading = false, 200));
       this.$store.dispatch('gettingAuthUser').then(() => {
            if (!this.authUser) this.$store.commit('removeAuthorize');
        }).catch(() => {
            if (!this.authUser) this.$store.commit('removeAuthorize');
        })
    }
}
</script>

<style>
.loading{
    background: #fff;
    z-index:10;
    position:fixed;
    height:100vh;
    width:100vw;
}
</style>
