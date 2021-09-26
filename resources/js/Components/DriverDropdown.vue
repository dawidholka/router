<template>
    <AutoComplete
        ref="autocomplete"
        class="w-full"
        :model-value="modelValue"
        :multiple="multiple"
        :suggestions="filteredDrivers"
        :force-selection="forceSelection"
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
    name: 'DriverDropdown',
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
            filteredDrivers: null,
            selectedDrivers: [],
        };
    },
    methods: {
        getDriverByName(data) {
            return axios.get(this.route('drivers.search', {params: data})).then(res => res);
        },
        search(event) {
            this.getDriverByName(event.query).then((data) => {
                this.filteredDrivers = data.data;
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
