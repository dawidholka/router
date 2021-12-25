<template>
    <div class="field">
        <label :for="name">{{ label }}</label>
        <InputText
            :id="name"
            class="w-full"
            v-model="modelValue"
            :type="type"
            :class="{'p-invalid': error}"
            @input="$emit('update:modelValue', $event.target.value)"
        />
        <small v-if="error" class="p-invalid">
            {{ error }}
        </small>
    </div>
</template>

<script>
import InputText from "primevue/inputtext";

export default {
    name: "RouterInputText",
    components: {
        InputText
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
        placeholder: {
            type: String,
            default: null,
        },
        type: {
            type: String,
            default: "text"
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

<style scoped>

</style>
