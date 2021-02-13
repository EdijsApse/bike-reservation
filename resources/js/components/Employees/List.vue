<template>
    <div class="row">
        <div class="col-12 col-md-8">
            <h1>Employees</h1>
            
            <div v-if="listLoading" class="d-flex justify-content-center py-2">
                <div class="spinner-border" role="status"></div>
            </div>

            <div v-else class="list">
                <div v-if="!employees.length" class="no-items">
                    <h3>No employees added</h3>
                </div>
                <div v-else>
                    <table class="table table-striped w-auto">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Surname</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="employee in employees" :key="employee.id">
                                <td>{{ employee.id }}</td>
                                <td>{{ employee.name }}</td>
                                <td>{{ employee.surname }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <h2>Add Employee</h2>
            <div class="form-wrapper">
                <div class="spinner-wrapper" v-if="loading">
                    <div class="spinner-border" role="status"></div>
                </div>
                <form>
                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" v-model="name" class="form-control" placeholder="Enter employees name"/>
                        <div class="form-error" v-if="$v.name.$dirty">
                            <p class="error" v-if="!$v.name.required">Name cannot be blank</p>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="surname">Surname</label>
                        <input id="surname" type="text" v-model="surname" class="form-control" placeholder="Enter employees surname"/>
                        <div class="form-error" v-if="$v.surname.$dirty">
                            <p class="error" v-if="!$v.surname.required">Surname cannot be blank</p>
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
            surname: '',
            loading: false,
            listLoading: false
        }
    },
    computed: {
        ...mapGetters(['employees'])
    },

    async created() {
        if (!this.employees.length) {
            this.listLoading = true;

            await axios.get('/employees')
            .then(res => {
                const { employees } = res.data;
                
                if (employees) this.$store.dispatch('addEmployees', employees);

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

            await axios.post('/employees', {
                name: this.name,
                surname: this.surname
            })
            .then(res => {
                const { employee, message, success } = res.data;
                
                if (message) {
                    this.$store.dispatch('addMessage', message);
                }

                if (success) {
                    this.$store.dispatch('pushEmployee', employee);
                    this.name = '';
                    this.surname = '';
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
        name: { required },
        surname: { required }
    }
}
</script>