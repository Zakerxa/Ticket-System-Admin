<template>
  <div>

      <div class="sidebar-mini layout-fixed">
        <div class="wrapper">

            <adminlteNavSlider @searching="searching" :noti="noti"/>

            <!-- Contect -->
            <div class="content-wrapper">
                <div class="content-header">

                   <Dashboard @searchOver="searchOver" @reading="reading" :search="search"/>
                </div>
            </div>

            <div class="main-footer">
                <div class="row justify-content-center">
                     <div class="col-12 text-center pt-4 pb-4">
                        <small class="text-muted">Copyright @2022 Zakerxa-Portfolio.All rights reserved.</small>
                    </div>
                </div>
            </div>

        </div>
       </div>


  </div>
</template>

<script>
import { mapGetters } from 'vuex';
import adminlteNavSlider from './adminlteNavSlider.vue';
import Dashboard from './components/Dashboard.vue';
export default {
    data () {
        return {
            adminLoading : true,
            search       : '',
            noti         : 0,
            searchend    : '',
        }
    },
    components:{
        adminlteNavSlider,
        Dashboard
    },
    methods: {
        searching(e){
          this.search = e
        },
        searchOver(){
          this.search = '';
        },
        review(){
            this.$http.get('/api/user/tickets').then( (response) => this.noti = response.data.noti);
        }
    },
    computed: { ...mapGetters(['auth', 'authUser'])},
    mounted () {
        this.$nextTick(()=>  setTimeout(() => this.adminLoading = false, 300));
        this.$store.dispatch('gettingAuthUser').then(() => {
            if (this.authUser) this.$http('/api/user/tickets').then( (response) => this.noti = response.data.noti);
        }).catch(() => {
            if (!this.authUser) this.$store.commit('removeAuthorize');
        })
    }
}
</script>

<style></style>
