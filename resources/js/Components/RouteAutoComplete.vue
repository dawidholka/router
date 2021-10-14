<template>
    <AutoComplete
        ref="autocomplete"
        class="w-full"
        :model-value="modelValue"
        :multiple="multiple"
        :suggestions="filteredRoutes"
        :force-selection="forceSelection"
        placeholder="Wpisz numer trasy, aby wyszukać..."
        field="name"
        @update:modelValue="updateValue"
        @complete="search($event)"
        @keyup.enter="addValue()"
    />
</template>

<script>
import AutoComplete from 'primevue/autocomplete';
import axios from "axios";

export default {
    name: 'RouteAutoComplete',
    components: {
        AutoComplete
    },
    props: {
        modelValue: {
            type: Array,
            default: () => []
        },
        multiple: {
            type: Boolean,
            default: false
        },
        forceSelection: {
            type: Boolean,
            default: false
        }
    },
    emits: ['update:modelValue'],
    data() {
        return {
            filteredRoutes: null,
            selectedRoutes: [],
        };
    },
    methods: {
        getRouteByName(data) {
            return axios.get(this.route('routes.search', {params: data})).then(res => res);
        },
        search(event) {
            this.getRouteByName(event.query).then((data) => {
                this.filteredRoutes = data.data;
            });
        },
        updateValue(event) {
            this.$emit('update:modelValue', event);
        },
        addValue() {
        }
    }
};
</script>

<style scoped>
.w-full ::v-deep .p-inputtext{
    width: 100%;
}

</style>
