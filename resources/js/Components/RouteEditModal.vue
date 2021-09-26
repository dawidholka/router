<template>
    <Dialog
        v-model:visible="dialogVisible"
        header="Edycja trasy"
        :closable="false"
        :modal="true"
    >
        <div class="formgrid grid">
            <div class="field col">
                <label for="delivery_date">Data</label>
                <Calendar
                    id="delivery_date"
                    class="w-full"
                    v-model="form.delivery_date"
                    date-format="yy-mm-dd"
                    :manual-input="false"
                    :class="{'p-invalid': form.errors.delivery_date}"
                />
                <small v-if="form.errors.delivery_date" class="p-invalid">
                    {{ form.errors.delivery_date }}
                </small>
            </div>
            <div class="field col">
                <label for="driver">Kierowca</label>
                <DriverDropdown
                    id="driver"
                    class="w-full"
                    v-model="form.driver"
                />
                <small v-if="form.errors.driver" class="p-invalid">
                    {{ form.errors.driver }}
                </small>
            </div>
        </div>
        <div class="field">
            <label for="note">Notatka</label>
            <Textarea
                id="note"
                class="w-full"
                rows="5"
                v-model="form.note"
            />
            <small v-if="form.errors.note" class="p-invalid">
                {{ form.errors.note }}
            </small>
        </div>

        <template #footer>
            <Button
                label="Anuluj"
                icon="pi pi-times"
                class="p-button-text"
                @click="onClose"
            />
            <Button
                label="Zapisz"
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

export default {
    name: 'RouteEditModal',
    components: {
        Dialog,
        Checkbox,
        Calendar,
        DriverDropdown,
        Textarea,
        Button
    },
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
    mixins: [FlashMessage],
    emits: ['update:visible'],
    data() {
        return {
            dialogVisible: this.visible,
            form: this.$inertia.form({
                'delivery_date': new Date(this.model.delivery_time),
                'note': this.model.note,
                'driver': this.model.driver,
            }),
        };
    },
    updated() {
        if (this.visible) {
            this.dialogVisible = this.visible;
        }
    },
    methods: {
        onClose() {
            this.$emit('update:visible', false);
            this.dialogVisible = false;
            this.form.clearErrors();
            this.form.reset();
        },
        onSubmit() {
            this.form.put(this.route('routes.update', this.model.id), {
                onSuccess: () => {
                    this.flashSuccess('Zapisano trasę');
                    this.onClose();
                    this.$inertia.get(this.route('routes.edit', this.model.id));
                }
            })
        }
    }
};
</script>
