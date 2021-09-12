<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col">
                    <Card>
                        <template #content>
                            <DataTable
                                class="p-datatable-sm"
                                :value="routeWaypoints"
                                :loading="loadingWaypoints"
                                responsiveLayout="scroll"
                                :reorderableColumns="true"
                                striped-rows
                                data-key="id"
                                selection-mode="multiple"
                                :meta-key-selection="false"
                                v-model:selection="selectedWaypoints"
                                @rowReorder="onRowReorder"
                            >
                                <template #header>
                                    <div class="flex justify-content-between align-content-center">
                                        <h5>Punkty</h5>
                                        <div>
                                            <Button
                                                class="p-button-sm ml-1 p-button-info"
                                                type="button"
                                                label="Podgląd"
                                                @click="show"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <Button
                                                class="p-button-sm ml-1 p-button-help"
                                                type="button"
                                                label="Geolokalizuj"
                                                @click="geocodeAll"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <Button
                                                class="p-button-sm ml-1"
                                                type="button"
                                                label="Zaznaczone"
                                                @click="toggleSelectedMenu"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <TieredMenu
                                                id="overlay_tmenu"
                                                ref="selectedMenu"
                                                :model="selectedMenu"
                                                :popup="true"
                                            />
                                            <Button
                                                class="p-button-sm ml-1"
                                                type="button"
                                                label="Dodaj punkt"
                                                @click="toggleAddMenu"
                                                aria-haspopup="true"
                                                aria-controls="overlay_tmenu"
                                            />
                                            <TieredMenu
                                                id="overlay_tmenu"
                                                ref="addMenu"
                                                :model="addMenu"
                                                :popup="true"
                                            />
                                        </div>
                                    </div>
                                </template>
                                <Column :rowReorder="true" headerStyle="width: 3rem" :reorderableColumn="false"/>
                                <Column field="id" header="Przystanek"></Column>
                                <Column field="name" header="Nazwa"></Column>
                                <Column field="address" header="Adres"></Column>
                                <Column field="city" header="Miasto"></Column>
                                <Column field="geocoded" header="GEO"></Column>
                                <Column field="quantity" header="Opcje"></Column>
                            </DataTable>
                        </template>
                        <template #footer>
                            <Button
                                label="Zapisz kolejność"
                                class="p-button-sm"
                                :loading="reorderForm.processing"
                                @click="saveOrder"
                            />
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
        FileUpload
    },
    props: {
        viewRoute: {
            type: Object,
            required: true
        },
        waypoints: {
            type: Array,
            required: true
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
        show() {
            this.$inertia.get(this.route('routes.show', this.viewRoute.id));
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
