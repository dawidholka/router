<template>
    <Dialog
        v-model:visible="dialogVisible"
        header="Optymalizacja trasy"
        :closable="false"
        :modal="true"
    >
        <div class="field">
            <label for="optimize_methods">Sposób optymalizacji*</label>
            <Dropdown
                id="optimize_methods"
                class="w-full"
                :options="optimizeMethods"
                v-model="form.method"
                option-label="label"
                option-value="value"
            />
            <small v-if="form.errors.method" class="p-invalid">
                {{ form.errors.method }}
            </small>
        </div>
        <div v-if="form.method === 'routexl' || form.method === 'routexl_manual'" class="field">
            <label for="optimize_methods">Punkt końcowy trasy*</label>
            <Dropdown
                id="optimize_methods"
                class="w-full"
                :options="lastLocationOptions"
                v-model="form.last_location"
                option-label="label"
                option-value="value"
            />
            <small v-if="form.errors.last_location" class="p-invalid">
                {{ form.errors.last_location }}
            </small>
        </div>
        <div v-if="form.method === 'routexl_manual'" class="field">
            <Button
                class="w-full p-button-info"
                icon="pi pi-file"
                label="Generuj plik .txt"
                @click="generateTxt"
            />
        </div>
        <div v-if="form.method === 'routexl_manual'" class="field">
            <label for="file">Plik z trasą*</label>
            <FileUpload
                id="file"
                class="w-full"
                mode="basic"
                :maxFileSize="1000000"
                :custom-upload="true"
                @select="onUpload"
            />
            <small v-if="form.errors.file" class="p-invalid">
                {{ form.errors.file }}
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
import Dialog from "primevue/dialog";
import Button from "primevue/button";
import Dropdown from "primevue/dropdown";
import FileUpload from "primevue/fileupload";

export default {
    name: "RouteOptimizeModal",
    components: {
        Dialog,
        Button,
        Dropdown,
        FileUpload
    },
    props: {
        visible: {
            type: Boolean,
            default: false
        },
        routeId: {
            type: Number,
            required: true
        }
    },
    emits: ['update:visible'],
    data() {
        return {
            dialogVisible: this.visible,
            form: this.$inertia.form({
                method: null,
                last_location: 'driver',
                file: null,
            }),
            optimizeMethods: [
                {value: 'routexl', label: 'RouteXL'},
                {value: 'routexl_manual', label: 'RouteXL Ręczna'},
                {value: 'open_route_service', label: 'OpenRouteService'},
            ],
            lastLocationOptions: [
                {value: 'driver', label: 'Punkt końcowy kierowcy'},
                {value: 'last_waypoint', label: 'Ostatni punkt trasy'},
                {value: 'company', label: 'Siedziba firmy'},
            ]
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
            this.form.post(this.route('routes.optimize', this.routeId), {
                onSuccess: () => {
                    this.onClose();
                    this.$inertia.get(this.route('routes.edit', this.routeId));
                }
            })
        },
        generateTxt() {
            const data = {
                last_location: this.form.last_location
            };
            const paramsString = (new URLSearchParams(data)).toString();
            const url = this.route('routes.generate-txt',this.routeId);

            window.open(`${url}?${paramsString}`, '_blank');
        },
        onUpload(event) {
            if (event.files && event.files[0]) {
                this.form.file = event.files[0];
            }
        },

    }
}
</script>

<style scoped>

</style>
