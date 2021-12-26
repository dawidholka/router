<template>
    <Dialog
        v-model:visible="dialogVisible"
        :header="$t('common.edit')"
        :closable="false"
        :modal="true"
    >
        <RouterInputText
            name="name"
            :label="$t('common.name')"
            :error="form.errors.name"
            v-model="form.name"
        />

        <RouterColorDropdown
            name="color"
            :label="$t('common.color')"
            :error="form.errors.color"
            v-model="form.color"
        />

        <div class="field">
            <label for="driver">{{ $t('common.driver') }}</label>
            <DriverDropdown
                id="driver"
                class="w-full"
                v-model="form.driver"
            />
            <small v-if="form.errors.driver" class="p-invalid">
                {{ form.errors.driver }}
            </small>
        </div>

        <template #footer>
            <Button
                :label="$t('common.cancel')"
                icon="pi pi-times"
                class="p-button-text"
                @click="onClose"
            />
            <Button
                :label="$t('common.save')"
                icon="pi pi-check"
                class="p-button-text"
                :loading="form.processing"
                @click="onSubmit"
            />
        </template>
    </Dialog>
</template>

<script>
import Dialog from 'primevue/dialog';
import Checkbox from 'primevue/checkbox';
import Calendar from "primevue/calendar";
import DriverDropdown from "./DriverDropdown";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import FlashMessage from "../Services/FlashMessage";
import {useForm} from "@inertiajs/inertia-vue3";
import {onUpdated, ref} from "vue";
import RouterInputText from "./RouterInputText";
import RouterColorDropdown from "./RouterColorDropdown";

export default {
    name: 'ZoneEditModal',
    components: {RouterColorDropdown, RouterInputText, Dialog, Checkbox, Calendar, DriverDropdown, Textarea, Button},
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        model: {
            type: Object,
            required: true
        }
    },
    setup(props, {emit, route}) {
        let dialogVisible = ref(props.visible);
        const form = useForm({
            name: props?.model?.name ?? null,
            color: props?.model?.color ?? null,
            driver: props?.model?.driver ?? null,
        })

        onUpdated(() => {
            if (props.visible) {
                dialogVisible.value = props.visible;
                form.name = props.model.name;
                form.color = props.model.color;
                form.driver = props.model?.driver ?? null;
            }
        });

        function onSubmit() {
            form.put(window.route('zones.update', props.model.id), {
                onSuccess: () => {
                    onClose();
                }
            })
        }

        function onClose() {
            emit('update:visible', false);
            dialogVisible.value = false;
            form.clearErrors();
            form.reset();
        }

        return {dialogVisible, form, onSubmit, onClose};
    },

    mixins: [FlashMessage],
    emits: ['update:visible'],
};
</script>
