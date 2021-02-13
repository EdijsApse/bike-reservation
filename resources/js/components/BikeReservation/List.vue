<template>
    <div class="row">
        <div class="col-12 col-md-8">
            <h1>Bike Reservation</h1>
            
            <div v-if="listLoading" class="d-flex justify-content-center py-2">
                <div class="spinner-border" role="status"></div>
            </div>

            <div v-else class="list">
                <div v-if="!bikeReservation.length" class="no-items">
                    <h3>No reservation created</h3>
                </div>
                <div v-else>
                    <table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Bike</th>
                                <th>Employee</th>
                                <th>From</th>
                                <th>To</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="reservation in bikeReservation" :key="reservation.id">
                                <td>{{ reservation.id }}</td>
                                <td>{{ reservation.bike }}</td>
                                <td>{{ reservation.employee }}</td>
                                <td>{{ reservation.reserved_from }}</td>
                                <td>{{ reservation.reserved_to }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h2>Create Reservation</h2>
            <div class="form-wrapper">
                <div class="spinner-wrapper" v-if="loading">
                    <div class="spinner-border" role="status"></div>
                </div>
                <form>
                    <div class="form-group">
                        <label for="bike">Bike</label>
                        <select id="bike" v-model="bike" class="form-control">
                            <option value="" disabled selected>Select bike from list</option>
                            <option v-for="bike in bikes" :key="bike.id" :value="bike.id">{{ bike.name }}</option>
                        </select>
                        <div class="form-error" v-if="$v.bike.$dirty">
                            <p class="error" v-if="!$v.bike.required">Please select bike</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="employee">Employee</label>
                        <select id="employee" v-model="employee" class="form-control">
                            <option value="" disabled selected>Select employee from list</option>
                            <option v-for="employee in employees" :key="employee.id" :value="employee.id">{{ employee.name }} {{ employee.surname }}</option>
                        </select>
                        <div class="form-error" v-if="$v.employee.$dirty">
                            <p class="error" v-if="!$v.employee.required">Please select employee</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="from">From</label>
                        <input id="from" type="datetime-local" v-model="from" class="form-control"/>
                        <div class="form-error" v-if="$v.from.$dirty">
                            <p class="error" v-if="!$v.from.required">Select start time</p>
                            <p class="error" v-if="!$v.from.isPassed">Date already passed</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="to">To</label>
                        <input id="to" type="datetime-local" v-model="to" class="form-control"/>
                        <div class="form-error" v-if="$v.to.$dirty">
                            <p class="error" v-if="!$v.to.required">Select end time</p>
                            <p class="error" v-if="!$v.to.isPassed">Date already passed</p>
                            <p class="error" v-if="!$v.to.isDatePassed">Date to must be freater then date from</p>
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

const isDatePassed = (to, from = null) => {
    const dateFrom = from ? new Date(from) : new Date();
    const dateTo = new Date(to);

    if (dateFrom.getTime() > dateTo.getTime()) return false;

    return true;
}

export default {
    data () {
        return {
            bike: '',
            employee: '',
            from: '',
            to: '',
            loading: false,
            listLoading: false
        }
    },
    computed: {
        ...mapGetters(['bikes', 'employees', 'bikeReservation'])
    },

    async created() {
        this.listLoading = true;

        await axios.get('/bike-reservation')
        .then(res => {
            const { bikes, employees, bikeReservation } = res.data;

            if (bikeReservation) this.$store.dispatch('addBikeReservation', bikeReservation);

            if (employees) this.$store.dispatch('addEmployees', employees);

            if (bikes) this.$store.dispatch('addBikes', bikes);

            this.listLoading = false;
        })
        .catch(err => {
            this.listLoading = false;
            this.$store.dispatch('addMessage', err.message);
        })
    },

    methods: {
        async create() {
            this.$v.$touch();

            if (this.$v.$error) return;

            this.loading = true;

            await axios.post('/bike-reservation', {
                bike_id: this.bike,
                employee_id: this.employee,
                reserved_from: this.from,
                reserved_to: this.to
            })
            .then(res => {
                const { reservation, success, message } = res.data;
                
                if (success) {
                    this.$store.dispatch('pushBikeReservation', reservation);
                    this.bike = '';
                    this.employee = '';
                    this.to = '';
                    this.from = '';
                    this.$v.$reset();
                }

                if (message) {
                    this.$store.dispatch('addMessage', message);
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
        bike: { required },
        employee: { required },
        from: {
            required,
            isPassed: val => isDatePassed(val)
        },
        to: { 
            required, 
            isPassed: val => isDatePassed(val),
            isDatePassed: function(val) {
                return isDatePassed(val, this.from);
            }
        }
    }
}
</script>