<template>
<div class="container">
    <div class="row justify-content-center pt-5">

        <div class="col-12 text-start">
            <h2 class="p-2">Ticket Log</h2>
        </div>

        <div v-if="log" class="col-12 text-start">

            <div v-for="(log,index) in log" :key="index">
                <p>{{index+1}}. {{log.user.name}}
                    <span v-if="log.title == 'deleted'" class="text-danger">{{log.title}}</span>
                    <span v-else-if="log.title == 'created'" class="text-success">{{log.title}}</span>
                    <span v-else-if="log.title == 'updated'" class="text-primary">{{log.title}}</span>
                    <span v-else-if="log.title == 'rejected'" class="text-danger">{{log.title}}</span>
                    <span v-else-if="log.title == 'checked'" class="text-success">{{log.title}}</span>
                    <span v-else class="text-success">{{log.title}}</span>
                    a ticket [ <span class="text-primary">{{log.token}}</span> ] {{log.created_at}}.</p>
            </div>

            <div class="col-12">
                <button v-if="(authUser.role_id == 3)" @click="clearLog()" class="btn btn-outline-danger btn-sm">Clear Logs</button>
            </div>

        </div>

        <div v-else class="col-12 text-center">
            <h4 class="text-muted pt-5">Empty Log !</h4>
        </div>

    </div>
</div>
</template>


<script>
import {
    mapGetters
} from 'vuex';
export default {
    computed: mapGetters(['auth', 'authUser', 'log']),
    mounted() {
        this.$store.dispatch('gettingAuthUser').then(() => {
            if (this.authUser) this.$store.dispatch('gettingLog');
        }).catch(() => {
            if (this.authUser) this.$store.dispatch('gettingLog');
        })
    },
    methods: {
        clearLog() {
            this.$store.dispatch('clearLog');
        }
    }
}
</script>


<style>

      </style>
