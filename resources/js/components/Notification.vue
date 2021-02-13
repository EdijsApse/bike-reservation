<template>
    <transition name="fade" mode="out-in">
        <div class="notification" v-if="message">
            <div class="d-flex justify-content-between align-items-start">
                <h4>Notification</h4>
                <div class="close" @click="close">
                    <i class="fas fa-times"></i>
                </div>
            </div>
            <p>{{ message }}</p>
        </div>
    </transition>
</template>

<script>

import { mapGetters } from 'vuex';

export default {

    computed: {
        ...mapGetters(['message'])
    },
    methods: {
        close() {
            this.$store.dispatch('addMessage', null);
        }
    }
}
</script>