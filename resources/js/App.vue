<template>
    <div class="position-relative">
    
        <notification></notification>

        <navigation></navigation>
    
        <div class="container">
            <transition name="fade" mode="out-in">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<script>

import Navigation from './components/Navigation';
import Notification from './components/Notification';

export default {
    components: {
        'navigation': Navigation,
        'notification': Notification
    }

}
</script>