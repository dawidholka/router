<template>
    <div>
        <AppLayout>
            <div class="card">
                <h5>Utworzone trasy</h5>
                <div v-for="element in routes" :key="element.id">
                    <Link :href="route('routes.edit', element.id)">
                        Trasa #{{ element.id }} - {{ element.note }}
                    </Link>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import {Link} from "@inertiajs/inertia-vue3";

export default {
    name: "Result",
    components: {
        AppLayout,
        Link
    },
    props: {
        routes: {
            type: Array,
            required: true
        }
    }
}
</script>

<style scoped>

</style>
