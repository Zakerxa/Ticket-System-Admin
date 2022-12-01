<template>
<div class="container-fluid">
    <div class="row">
        <h3 class="p-3 pl-0">Dashboard - {{authUser.role.name}}</h3>
    </div>
    <div class="row pb-3" v-show="checkIds.length">
        <div class="col">
            <button class="btn btn-sm btn-outline-dark" @click="selectAllForms()">{{selectAll ? 'Deselect' : 'Select'}} All</button>
        </div>
        <div class="col">
            <button class="btn btn-sm btn-outline-primary ml-1">{{checkIds.length ? 'Selected ' + checkIds.length : ''}}</button>
        </div>
        <div class="col text-end">
            <button v-if="(authUser.role_id == 2)" class="btn btn-sm btn-outline-success" @click="checkTickets">{{checkIds.length >= 2 ? 'Checked All' : 'Checked'}}</button>
            <button v-if="(authUser.role_id == 3)" class="btn btn-sm btn-outline-success" @click="approveTickets">{{checkIds.length >= 2 ? 'Approved All' : 'Approved'}}</button>
        </div>
    </div>

    <!-- Reading Components -->
    <div v-if="review" class="row justify-content-center">
        <div class="row p-2" style="background:#fff">
            <table v-if="(reader && authUser)" class="table border">
                <thead>

                </thead>
                <tbody class="fw-bold">
                    <tr v-if="(authUser.role_id == 3)">
                       <th scope="row">Name</th>
                        <td>: {{reader.user.name}}</td>
                    </tr>
                    <tr v-if="(authUser.role_id == 3)">
                       <th scope="row">Email</th>
                        <td>: {{reader.user.email}}</td>
                    </tr>
                    <tr v-if="(authUser.role_id == 3)">
                       <th scope="row">Role</th>
                        <td>: {{reader.user.role.name}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Ticket ID</th>
                        <td>: {{reader.ticket}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Title</th>
                        <td>: {{reader.title}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Description</th>
                        <td>: {{reader.description}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Time</th>
                        <td>: {{reader.created_at}}</td>
                    </tr>
                    <tr>
                        <th scope="row">Status</th>
                        <td v-if="(reader.status == 0)">: Waiting</td>
                        <td v-if="(reader.status == 1)">: Reject</td>
                        <td v-if="(reader.status == 2)">: Checked</td>
                        <td v-if="(reader.status == 3)">: Approved</td>
                    </tr>

                </tbody>
            </table>
        </div>

        <div class="col text-center pt-4">
            <p @click="review=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-dark">Go Back</p>
        </div>
        <div class="col text-center pt-4">
            <p v-if="(authUser.role_id == 3)" @click="reviewTicket(reader,0);review=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-danger">Reject</p>
            <p v-else @click="reviewTicket(reader,1);review=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-danger">Reject</p>
        </div>
        <div class="col text-center pt-4">
            <p v-if="(authUser.role_id == 3)" @click="reviewTicket(reader,3);review=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-success">Approved</p>
            <p v-else @click="reviewTicket(reader,2);review=false;vuePaginate(current??null)" class="btn btn-sm btn-outline-primary">Check</p>
        </div>
    </div>

    <!-- All Forms Data -->
    <div v-else class="row">


        <div v-if="auth && forms.length" class="position-relative">
            <div v-show="readyFormLoading" class="overlayLoading">
                <div class="row">
                    <img src="/icon/loading.png" style="width:80px;" alt="">
                </div>
            </div>
            <div class="table-responsive">

                <table v-if="forms && forms.length >= 1" class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">No</th>
                            <th scope="col" class="text-start">Ticket</th>
                            <th scope="col" class="text-start">Subject</th>
                            <th scope="col" class="">Time</th>
                            <th scope="col">Status</th>
                            <th scope="col">Review</th>
                        </tr>
                    </thead>
                    <tbody v-for="(ticket,index) in forms" :key="index">

                        <tr>
                            <th><input class="text-end" type="checkbox" @change="check($event)" :value="ticket.id" :id="ticket.id"></th>
                            <td scope="row">{{index+1}}</td>
                            <td class="text-start">{{ticket.ticket}}</td>
                            <td class="text-start">{{ticket.title}}</td>
                            <td class="text-start">{{ticket.updated_at}}</td>
                            <td v-if="ticket.status == 0" class="text-warning">Waiting</td>
                            <td v-else-if="ticket.status == 1" class="text-danger">Rejected</td>
                            <td v-else-if="ticket.status == 2" class="text-primary"> Checked</td>
                            <td v-else-if="(ticket.status == 3)" class="text-success"> Approved</td>
                            <td>
                                <font-awesome-icon @click="reviewTk(ticket)" class="btn btn-sm btn-outline-dark" icon="fa-solid fa-pen-to-square" />
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div v-else class="col-12 text-center">
                    <h4 class="text-muted pt-5">Empty Ticket !</h4>
                </div>

            </div>

        </div>

        <!-- No Data Found -->
        <div v-else-if="forms.length == 0" class="col-12 text-center p-3">
            <h3 class="pt-5">No Ticket Here {{search}}</h3>
            <button v-show="checkIds.length" @click="searchOver" class="btn btn-sm btn-outline-dark mt-5">Go Back</button>
        </div>

        <!-- Loading -->
        <div v-else class="col-12 text-center p-3" >
            <div class="overlayLoading">
                <div class="row">
                    <p>Loading ....</p>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="col-12 pt-3 text-center">
            <vue-pagination @event="vuePaginate" :meta-data="paginations" :onSides="1"></vue-pagination>
        </div>
    </div>

    <!-- Modal SuccessForm -->
    <div class="col-12">
        <div class="modal fade" id="mailSuccess" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content boxshadow border-0">
                    <div class="modal-header">
                        <h3 class="modal-title fs-5 fw-bold" style="letter-spacing:1px;" id="staticBackdropLabel">
                            <font-awesome-icon icon="fa-solid fa-check-circle" class="text-success" /> Mail Sent
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
</template>

<script>
import {
    mapGetters
} from 'vuex'
export default {
    data() {
        return {
            loadingIcon : 'Loading...',
            endpoint: 'api/user/tickets?page=',
            perPage: [5],
            /* default path => '&per_page=' (Or) change [2,'&UrPath='] */
            paginations: {},
            forms: [],
            reader: [],
            review: false,
            current: null,
            vsearch: '',

            readyFormLoading: false,

            selectAll: false,
            checkIds: [],
            delIcon: [],
            // Mailing System
        }
    },
    props: ['search'],
    created() {
        this.startInit();
    },
    methods: {
        vuePaginate(e) {
            this.current = e;
            console.log(this.current)
            e ? this.startInit(e[0] + e[1]) : this.startInit();
        },
        startInit(e) {
            let endpoint = e ?? (this.perPage[1] ?? '&per_page=') + this.perPage[0];
            this.getPaginateWithUsers(endpoint + (this.vsearch ? '&search=' + this.vsearch : '')).then(res => this.insertData(res));
        },
        insertData(res) {
            //  If something is change Reselect All check
            if (this.checkIds.length >= 1 && this.selectAll) this.selectAllForms();
            // If something is change refresh checkbox
            this.checkBoxDef();
            // When you delete all checkbox last pagination that will refetch data back
            if ((!this.vsearch) && (!res.data.length)) {
                console.log("No search & Data");
                this.readyFormLoading = false;
                if (!this.current) {
                    this.vsearch = '';
                    this.forms = [];
                    return;
                }
                if ((this.current[0] - 1)) this.vuePaginate([this.current[0] - 1, this.current[1]]);
            }

            //   this.vsearch = '';
            console.log(res.data,"inserting Data");
            this.forms = res.data;
            this.paginations = {
                current_page: res.current_page,
                last_page: res.last_page,
                prev_page_url: res.prev_page_url,
                next_page_url: res.next_page_url,
                per_page: this.perPage
            }
            this.readyFormLoading = false;
        },
        searchOver() {
            this.vuePaginate(this.current ?? null);
            this.$emit('searchOver');
        },
        check(e) {
            this.$nextTick(() => {
                let id = e.target.value;
                console.log(id);
                if (e.target.checked) this.checkIds.push(parseInt(id));
                else this.checkIds.remove(parseInt(id));
                this.delIcon = this.checkIds;
            });
        },
        checkBoxDef(e, ctn) {
            this.checkIds = [];
            if (this.forms.length >= 1) {
                this.forms.map(res => {
                    document.getElementById(res.id).checked = e ?? false;
                    if (ctn) ctn.push(res.id);
                });
            }
        },
        selectAllForms() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                let ctn = [];
                this.checkBoxDef(true, ctn)
                const sameId = this.checkIds.filter(e => {
                    for (var i = 0; i < ctn.length; i++)
                        if (e.id == ctn[i].id) return true;
                    return null;
                });
                if (sameId.length > 0)
                    for (let i = 0; i < sameId.length; i++) this.checkIds.remove(sameId[i]);
                this.checkIds = ctn;
            } else this.checkBoxDef();
            this.delIcon = this.checkIds;
        },
        checkTickets() {
            if (this.checkIds.length >= 1 && confirm('Are you sure to check them all?')) {
                this.readyFormLoading = true;
                this.$http.post('api/user/tickets/checked/' + this.checkIds)
                    .then(res => {
                        if (res.data.response == 'success') this.vuePaginate(this.current ?? null);
                    })
                    .catch(err => console.log("Error", err));
            } else alert('Reselect form again.');
        },
        approveTickets(){
            if (this.checkIds.length >= 1 && confirm('Are you sure to approve them all?')) {
                this.readyFormLoading = true;
                this.$http.post('api/user/tickets/approved/' + this.checkIds)
                    .then(res => {
                        if (res.data.response == 'success') this.vuePaginate(this.current ?? null);
                    })
                    .catch(err => console.log("Error", err));
            } else alert('Reselect form again.');
        },
        getPaginateWithUsers(url) {
            return this.$http
                .get(this.endpoint + url)
                .then(res => res.data.data)
                .catch(err => console.log(err))
        },
        reviewTk(e) {
            //  If something is change Reselect All check
            if (this.checkIds.length >= 1 && this.selectAll) this.selectAllForms();
            // If something is change refresh checkbox
            this.checkBoxDef();
            // review Forms
            this.forms.find(res => {
                if (res.id == e.id) {
                    this.reader = res;
                    this.review = true;
                } else return null;
            });
        },
        reviewTicket(ticket,code){
            this.$store.dispatch('editTicket', {id:ticket.id,code:code}).then(() => {
                this.$emit('review');
                this.review = false;
                $('#modalSuccessForm').modal('hide');
            })
            .catch(() => $('#modalSuccessForm').modal('hide'));
        }
    },
    watch: {
        search(newvalue, old) {
            this.vsearch = newvalue;
            this.vuePaginate();
            return newvalue;
        }
    },
    computed: {...mapGetters(['auth', 'authUser'])},
    mounted() {
        this.$store.dispatch('gettingAuthUser').then(() => {
            if (this.authUser) this.$store.dispatch('getMyTicket');
        }).catch(() => {
            if (this.authUser) this.$store.dispatch('getMyTicket');
        })
    }
}
</script>

<style lang="scss" scoped>
.mailcontainer {
    cursor: pointer;
}


.overlayLoading {
    position: absolute;
    height: 100%;
    width: 100%;
    z-index: 9999;
    border: none;
    border-radius: 5px;
    background: rgba(255, 255, 255, 0.749);

    div {
        justify-content: center;
        position: absolute;
        align-items: center;
        height: 100%;
        width: 100%;
        margin: 0;
        padding: 0;

        img {
            -webkit-animation: spin 2s linear infinite;
            -moz-animation: spin 2s linear infinite;
            animation: spin 2s linear infinite;
        }

        @-moz-keyframes spin {
            100% {
                -moz-transform: rotate(360deg);
            }
        }

        @-webkit-keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
            }
        }

        @keyframes spin {
            100% {
                -webkit-transform: rotate(360deg);
                transform: rotate(360deg);
            }
        }
    }
}
</style>
