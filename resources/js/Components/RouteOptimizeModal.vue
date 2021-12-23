<template>
    <Dialog
        v-model:visible="dialogVisible"
        :header="$t('routes.optimizeRoute')"
        :closable="false"
        :modal="true"
    >
        <div class="field">
            <label for="optimize_methods">{{ $t('routes.optimizationMethod') }}*</label>
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
        <div v-if="form.method === 'routexl' || form.method === 'routexl_manual' || form.method === 'osrm'"
             class="field">
            <label for="optimize_methods">{{ $t('routes.endWaypointOfTheRoute') }}*</label>
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
                :label="$t('routes.generateTxt')"
                @click="generateTxt"
            />
        </div>
        <div v-if="form.method === 'routexl_manual'" class="field">
            <label for="file">{{ $t('routes.routeFile') }}*</label>
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
    emits: ['update:visible', 'optimized'],
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
                {value: 'routexl_manual', label: this.$t('routes.routeXlManual')},
                {value: 'open_route_service', label: 'OpenRouteService'},
                {value: 'osrm', label: 'OSRM'},
            ],
            lastLocationOptions: [
                {value: 'driver', label: this.$t('routes.driverEndPoint')},
                {value: 'last_waypoint', label: this.$t('routes.lastWaypoint')},
                {value: 'company', label: this.$t('routes.company')},
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
                    this.$emit('optimized');
                }
            })
        },
        generateTxt() {
            const data = {
                last_location: this.form.last_location
            };
            const paramsString = (new URLSearchParams(data)).toString();
            const url = this.route('routes.generate-txt', this.routeId);

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
