<template>
    <div class="row">
        <div class="col-12 col-md-8">
            <h1>Bikes</h1>
            
            <div v-if="listLoading" class="d-flex justify-content-center py-2">
                <div class="spinner-border" role="status"></div>
            </div>

            <div v-else class="list">
                <div v-if="!bikes.length" class="no-items">
                    <h3>No bikes added</h3>
                </div>
                <div v-else>
                    <table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bike</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="bike in bikes" :key="bike.id">
                                <td>{{ bike.id }}</td>
                                <td>{{ bike.name }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h2>Add Bike</h2>
            <div class="form-wrapper">
                <div class="spinner-wrapper" v-if="loading">
                    <div class="spinner-border" role="status"></div>
                </div>
                <form>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" v-model="name" class="form-control" placeholder="Enter name of bike"/>
                        <div class="form-error" v-if="$v.name.$dirty">
                            <p class="error" v-if="!$v.name.required">Name cannot be blank</p>
                        </div>
                    </div>
                    <button @click.prevent="create" class="btn btn-primary btn-main">Create</button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>

import axios from 'axios';
import { required } from 'vuelidate/lib/validators';
import { mapGetters } from 'vuex';

export default {
    data () {
        return {
            name: '',
            loading: false,
            listLoading: false
        }
    },
    computed: {
        ...mapGetters(['bikes'])
    },

    async created() {
        if (!this.bikes.length) {
            this.listLoading = true;

            await axios.get('/bikes')
            .then(res => {
                const { bikes } = res.data;
                
                if (bikes) {
                    this.$store.dispatch('addBikes', bikes);
                }

                this.listLoading = false;
            })
            .catch(err => {
                this.listLoading = false;
                this.$store.dispatch('addMessage', err.message);
            })
        }
    },

    methods: {
        async create() {
            this.$v.$touch();

            if (this.$v.$error) return;

            this.loading = true;

            await axios.post('/bikes', {
                name: this.name
            })
            .then(res => {
                const { bike, message, success } = res.data;

                if (message) {
                    this.$store.dispatch('addMessage', message);
                }
                
                if (success) {
                    this.$store.dispatch('pushBike', bike);
                    this.name = '';
                    this.$v.$reset();
                }

                this.loading = false;
            })
            .catch(err => {
                this.loading = false;
                this.$store.dispatch('addMessage', err.message);
            })
        }
    },

    validations: {
        name: { required }
    }
}
</script>