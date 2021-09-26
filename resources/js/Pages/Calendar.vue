<template>
    <div>
        <AppLayout>
            <FullCalendar :options="calendarOptions"/>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../Layouts/AppLayout";
import FullCalendar from "@fullcalendar/vue3";
import dayGridPlugin from '@fullcalendar/daygrid'

export default {
    name: "Result",
    components: {
        AppLayout,
        FullCalendar
    },
    data() {
        return {
            calendarOptions: {
                plugins: [ dayGridPlugin ],
                initialView: 'dayGridMonth',
                weekNumbers: true,
                eventLimit: true,
                editable: false,
                firstDay: 1,
                events: this.route('calendar.events'),
            },
        }
    },
}
</script>

<style scoped>

</style>
