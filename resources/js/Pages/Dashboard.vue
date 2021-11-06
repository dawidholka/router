<template>
    <app-layout>
        <div v-if="driversData.length" class="grid">
            <div v-for="driver in driversData" :key="driver.name" class="col-3">
                <div class="card text-center">
                    <h5>{{ driver.name }}</h5>
                    <ProgressBar :value="driver.percent">
                        {{ $t('dashboard.completed') }}: {{ parseInt(driver.percent).toString(10) }}%
                    </ProgressBar>
                    <p class="mt-3">
                        {{ $t('dashboard.delivered') }}: {{ driver.delivered }}
                    </p>
                    <p>
                        {{ $t('dashboard.problem') }}: {{ driver.problem }}
                    </p>
                    <p>
                        {{ $t('dashboard.undelivered') }}: {{ driver.undelivered }}
                    </p>
                    <Link :href="route('routes.show', driver.route_id)">
                        {{ $t('dashboard.viewRoute') }}
                    </Link>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue';
import ProgressBar from 'primevue/progressbar';
import {Link} from "@inertiajs/inertia-vue3";

export default {
    components: {
        AppLayout,
        ProgressBar,
        Link,
    },
    props: {
        driversData: {
            type: Array,
            default: () => []
        }
    }
}
</script>
