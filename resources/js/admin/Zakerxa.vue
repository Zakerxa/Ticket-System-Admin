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
          // Refresh the search key
          this.search = '';
        },
        review(){
            // Accepting reading cause of noti change
            this.$http.get('/api/user/tickets').then( (response) => this.noti = response.data.noti);
        }
    },
    computed: { ...mapGetters(['auth', 'authUser'])},
    created () {
        this.$http('/api/user/tickets').then( (response) => this.noti = response.data.noti);
    },
    mounted () {
        this.$nextTick(()=>  setTimeout(() => this.adminLoading = false, 300));
    }
}
</script>

<style></style>
