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
                        <h5>Zawartość</h5>
                        <ul>
                            <li v-for="item in deliveryContent" :key="item.name">{{ item.name }} - {{item.count}}</li>
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
                                @rowReorder="onRowReorder"
                            >
                                <template #header>
                                    <div class="flex justify-content-between align-content-center">
                                        <h5>Lista punktów</h5>
                                        <div>
                                            <Button
                                                class="p-button-sm ml-1"
                                                type="button"
                                                label="Edytuj trasę"
                                                @click="edit"
                                                aria-haspopup="true"
                                                aria-controls="overlay_menu"
                                            />
                                        </div>
                                    </div>
                                </template>
                                <Column field="stop_number" header="Przystanek"></Column>
                                <Column field="name" header="Nazwa"></Column>
                                <Column field="address" header="Adres"></Column>
                                <Column field="city" header="Miasto"></Column>
                                <Column field="status" header="Status"></Column>
                                <Column field="delivered_time" header="Data dostarczenia"></Column>
                                <Column header="Opcje">
                                    <template #body="slotProps">
                                        <Button
                                            v-if="slotProps.data.photo_uploaded"
                                            icon="pi pi-image"
                                            class="p-button-info p-button-sm mr-1"
                                            @click="showPhoto(slotProps.data.id)"
                                        />
                                    </template>
                                </Column>
                            </DataTable>
                        </template>
                    </Card>
                </div>
            </div>

            <Dialog
                header="Import pliku .xlsx"
                v-model:visible="importXlsxDialog"
                :closable="false"
                modal
            >
                <FileUpload
                    mode="basic"
                    :maxFileSize="1000000"
                    :custom-upload="true"
                    @select="onUpload"
                />

                <template #footer>
                    <Button
                        label="Anuluj"
                        @click="closeImportXlsxDialog"
                        class="p-button-text"
                    />
                    <Button
                        label="Importuj"
                        :loading="importForm.processing"
                        @click="importXlsx"
                    />
                </template>
            </Dialog>

            <DeleteDialog
                ref="deleteDialog"
                v-model:visible="deleteDialog"
                :loading="deletingModel"
                @delete="onDelete"
            />
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
import {Inertia} from "@inertiajs/inertia";

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
        MapWithMarkers
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
            routeWaypoints: this.waypoints,
            loadingWaypoints: false,
            selectedWaypoints: null,
            reorderForm: this.$inertia.form({
                waypoints: null
            }),
            selectedMenu: [
                {
                    label: 'Zaznacz wszystko',
                    icon: 'pi pi-fw pi-check',
                    command: () => this.selectAll()
                },
                {
                    label: 'Odzaznacz wszystko',
                    icon: 'pi pi-fw pi-ban',
                    command: () => this.deselectAll()
                },
                {
                    label: 'Usuń zaznaczone',
                    icon: 'pi pi-fw pi-trash',
                    command: () => this.deleteSelected()
                }
            ],
            addMenu: [
                {
                    label: 'Z bazy danych',
                    icon: 'pi pi-fw pi-table'
                },
                {
                    label: 'Import .xlsx',
                    icon: 'pi pi-fw pi-file',
                    command: () => this.openImportXlsxDialog()
                }
            ],
            importXlsxDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
            deleteDialog: false,
            deletingModel: false,
        }
    },
    methods: {
        onRowReorder(event) {
            this.routeWaypoints = event.value;
            // this.$toast.add({severity:'success', summary: 'Rows Reordered', life: 3000});
        },
        saveOrder() {

        },
        selectAll() {
            this.selectedWaypoints = this.routeWaypoints;
        },
        deselectAll() {
            this.selectedWaypoints = [];
        },
        deleteSelected() {
            const ids = this.selectedWaypoints.map((waypoint) => {
                return waypoint.id;
            });

            this.$inertia.post(this.route('routes.waypoints.destroy', this.viewRoute.id), {
                waypoint_ids: ids
            }, {
                onSuccess: () => {
                    this.deselectAll();
                    this.reloadWaypoints();
                }
            });
        },
        toggleSelectedMenu(event) {
            this.$refs.selectedMenu.toggle(event);
        },
        toggleAddMenu(event) {
            this.$refs.addMenu.toggle(event);
        },
        openImportXlsxDialog() {
            this.importXlsxDialog = true;
        },
        onUpload(event) {
            if (event.files && event.files[0]) {
                this.importForm.file = event.files[0];
            }
        },
        importXlsx() {
            this.importForm.post(this.route('routes.import-file', 1), {
                onSuccess: () => {
                    this.closeImportXlsxDialog();
                    this.reloadWaypoints();
                }
            })
        },
        closeImportXlsxDialog() {
            this.importXlsxDialog = false;
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
        geocodeAll() {
            this.$inertia.post(this.route('routes.waypoints.geocode', this.viewRoute.id), {}, {
                onSuccess: () => {
                    this.reloadWaypoints();
                }
            })
        },
        edit() {
            this.$inertia.get(this.route('routes.edit', this.viewRoute.id));
        },
        showPhoto(id) {
            window.open(this.route('waypoints.photo', id), '_blank');
        },
        deleteRoute() {
            this.deleteDialog = true;
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('routes.destroy', this.viewRoute.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
