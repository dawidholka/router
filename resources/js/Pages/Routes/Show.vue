<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div
                    v-if="viewRoute.status === 'ok'"
                    class="col-12"
                >
                    <MapWithMarkers
                        ref="map"
                        :api-key="$page.props.google_maps_api_key"
                        :markers="waypoints"
                        :driver-locations="driverLocations"
                    />
                </div>

                <div v-if="deliveryContent.length" class="col-12">
                    <div class="card">
                        <h5>{{ $t('common.content') }}</h5>
                        <ul>
                            <li v-for="item in deliveryContent" :key="item.name">{{ item.name }} - {{ item.count }}</li>
                        </ul>
                    </div>
                </div>

                <div class="col-12">
                    <Card>
                        <template #content>
                            <DataTable
                                class="p-datatable-sm"
                                :value="routeWaypoints"
                                :loading="loadingWaypoints"
                                responsiveLayout="scroll"
                                striped-rows
                                data-key="id"
                            >
                                <template #header>
                                    <div class="flex justify-content-between align-content-center">
                                        <h5>{{ $t('common.waypoints') }}</h5>
                                        <div>
                                            <Button
                                                class="p-button-sm ml-1"
                                                type="button"
                                                :label="$t('routes.editRoute')"
                                                @click="edit"
                                                aria-haspopup="true"
                                                aria-controls="overlay_menu"
                                            />
                                        </div>
                                    </div>
                                    <div style="text-align:left">
                                        <MultiSelect :modelValue="selectedColumns" :options="columns"
                                                     optionLabel="header" @update:modelValue="onToggle"
                                                     placeholder="Select Columns" style="width: 20em"/>
                                    </div>
                                </template>
                                <Column field="stop_number" :header="$t('common.stopNumber')"></Column>
                                <Column v-for="(col, index) of selectedColumns" :field="col.field" :header="col.header"
                                        :key="col.field + '_' + index"></Column>
                                <Column :header="$t('common.options')">
                                    <template #body="slotProps">
                                        <Button
                                            v-if="slotProps.data.photo_uploaded"
                                            icon="pi pi-image"
                                            class="p-button-info p-button-sm mr-1"
                                            v-tooltip.top="$t('common.downloadPhoto')"
                                            @click="showPhoto(slotProps.data.id)"
                                        />
                                    </template>
                                </Column>
                                <template #empty>
                                    {{ $t('points.empty') }}
                                </template>
                            </DataTable>
                        </template>
                    </Card>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import Card from "primevue/card";
import Divider from "primevue/divider";
import Button from "primevue/button";
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import DeleteDialog from "../../Components/DeleteDialog";
import TieredMenu from "primevue/tieredmenu";
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import MapWithMarkers from "../../Components/MapWithMarkers";
import MultiSelect from 'primevue/multiselect';

export default {
    name: "Show",
    components: {
        AppLayout,
        Card,
        Divider,
        Button,
        DeleteDialog,
        DataTable,
        Column,
        TieredMenu,
        Dialog,
        FileUpload,
        MapWithMarkers,
        MultiSelect
    },
    props: {
        viewRoute: {
            type: Object,
            required: true
        },
        waypoints: {
            type: Array,
            required: true
        },
        driverLocations: {
            type: Array,
            required: true,
        },
        deliveryContent: {
            type: Array,
            default: () => []
        }
    },
    data() {
        return {
            columns: null,
            selectedColumns: null,
            routeWaypoints: this.waypoints,
            loadingWaypoints: false,
            selectedWaypoints: null,
            reorderForm: this.$inertia.form({
                waypoints: null
            }),
            importXlsxDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            deleteDialog: false,
            deletingModel: false,
        }
    },
    created() {
        this.columns = [
            {field: 'name', header: this.$t('common.name')},
            {field: 'address', header: this.$t('common.address')},
            {field: 'city', header: this.$t('common.city')},
            {field: 'status', header: this.$t('common.status')},
            {field: 'delivered_time', header: this.$t('common.deliveredTime')},
            {field: 'driver_note', header: this.$t('common.driverNote')},
            {field: 'content', header: this.$t('common.content')},
            {field: 'quantity', header: this.$t('common.quantity')},
            {field: 'postcode', header: this.$t('common.postcode')},
        ];
        const savedColumns = JSON.parse(localStorage.getItem('routeShowColumns'));
        this.selectedColumns = savedColumns ?? this.columns;
    },
    methods: {
        onToggle(value) {
            this.selectedColumns = this.columns.filter(col => value.includes(col));
            localStorage.setItem('routeShowColumns', JSON.stringify(this.selectedColumns));
        },
        reloadWaypoints() {
            this.loadingWaypoints = true;
            this.$inertia.reload({
                only: ['waypoints'], onSuccess: () => {
                    this.routeWaypoints = this.waypoints;
                    this.loadingWaypoints = false;
                }
            });
        },
        edit() {
            this.$inertia.get(this.route('routes.edit', this.viewRoute.id));
        },
        showPhoto(id) {
            window.open(this.route('waypoints.photo', id), '_blank');
        },
    }
}
</script>

<style scoped>

</style>
