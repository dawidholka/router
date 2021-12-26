<template>
    <div class="field">
        <label :for="name">{{ label }}</label>
        <Dropdown
            :id="name"
            class="w-full"
            v-model="modelValue"
            :class="{'p-invalid': error}"
            option-value="value"
            option-label="name"
            :options="colors"
            @change="$emit('update:modelValue', $event.value)"
        >
            <template #option="slotProps">
                <div :style="{'color': slotProps.option.name}">
                    <span>{{ slotProps.option.name }}</span>
                </div>
            </template>
        </Dropdown>
        <small v-if="error" class="p-invalid">
            {{ error }}
        </small>
    </div>
</template>

<script>
import Dropdown from "primevue/dropdown";

export default {
    name: "RouterColorDropdown",
    components: {
        Dropdown
    },
    props: {
        name: {
            type: String,
            required: true
        },
        label: {
            type: String,
            required: true,
        },
        colors: {
            type: Array,
            default: [{"name": "aqua", "value": "aqua"}, {
                "name": "blueviolet",
                "value": "blueviolet"
            }, {"name": "cornflowerblue", "value": "cornflowerblue"}, {
                "name": "chartreuse",
                "value": "chartreuse"
            }, {"name": "coral", "value": "coral"}, {"name": "darkgray", "value": "darkgray"}, {
                "name": "darkorange",
                "value": "darkorange"
            }, {"name": "darksalmon", "value": "darksalmon"}, {
                "name": "hotpink",
                "value": "hotpink"
            }, {"name": "lightgreen", "value": "lightgreen"}, {"name": "yellowgreen", "value": "yellowgreen"}]
        },
        placeholder: {
            type: String,
            default: null,
        },
        modelValue: {},
        error: {
            type: String,
            default: null
        }
    },
    emits: ['update:modelValue']
}
</script>
