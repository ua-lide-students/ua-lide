<template>
    <div class="flex flex-col p-4 w-full">
        <!-- <div
            v-for="(notif, index) in notifs"
            :key="index"
            class="notif"
            :class="'notif-'+ notif.type"
        >
            <h3 v-if="notif.title">{{notif.title}}</h3>
            <p v-if="notif.message" v-text="notif.message"></p>
        </div> -->
        <v-alert
            data-cy="notification"
            v-for="(notif, index) in notifs"
            :key="index"
            class="bg-white w-full"
            :value="true"
            :type="notif.type"
            outline
            transition="scale-transition"
            >{{notif.message}}
        </v-alert>
    </div>
</template>

<script>
import {EventBus, events} from '../event-bus.js'
import { setTimeout } from 'timers';

export default {
    name: "LideNotifications",
    data(){
        return {
            notifs: [],
            lastId: 0,
        }
    },
    mounted(){
        EventBus.$on(events.NOTIFICATION, (payload) => {
            this.lastId++;
            const id = this.lastId;
            this.notifs.push({
                id: id,
                title: payload.title,
                message: payload.message || "",
                type: payload.type || "info"
            })
            console.log('Notifications : ', payload);
            setTimeout(() => {
                let pos = this.notifs.findIndex((elt) => elt.id === id);
                this.notifs.splice(pos, 1);
            }, payload.length)
        })
    }
}
</script>
