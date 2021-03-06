<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <Menubar :model="menuItems"/>
                    </div>
                </div>
                <div v-if="zones.length" class="col-12">
                    <div class="card">
                        <MapWithPolygons
                            ref="map"
                            :api-key="$page.props.google_maps_api_key"
                            :map-center="mapCenter"
                            :polygons="zones"
                        />
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <DataTable
                            ref="dt"
                            :value="zones"
                            data-key="id"
                            :paginator="true"
                            :rows="10"
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[5,10,25]"
                            :current-page-report-template="$tm('common.currentPageReportTemplate')"
                        >
                            <template #header>
                                <div class="table-header">
                                    <h5 class="m-0">
                                        {{ $t('common.zones') }}
                                    </h5>
                                </div>
                            </template>
                            <Column field="id" header="ID" :sortable="true" style="width: 150px;">
                                <template #body="slotProps">
                                    {{ slotProps.data.id }}
                                </template>
                            </Column>
                            <Column field="name" :header="$t('common.name')" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.name }}
                                </template>
                            </Column>
                            <Column field="color" :header="$t('common.color')" :sortable="true">
                                <template #body="slotProps">
                                    <span :style="{color: slotProps.data.color}">
                                    {{ slotProps.data.color }}
                                    </span>
                                </template>
                            </Column>
                            <Column field="driver" :header="$t('common.driver')" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.driver?.name ?? '-' }}
                                </template>
                            </Column>
                            <Column field="created_at" :header="$t('common.createdAt')" :sortable="false">
                                <template #body="slotProps">
                                    {{ slotProps.data.created_at }}
                                </template>
                            </Column>
                            <Column :header="$t('common.options')" style="width: 150px;">
                                <template #body="slotProps">
                                    <Button
                                        icon="pi pi-pencil"
                                        class="p-button-success p-button-sm mr-1"
                                        @click="edit(slotProps.data)"
                                    />
                                    <Button icon="pi pi-trash" class="p-button-sm p-button-danger"
                                            @click="showDeleteDialog(slotProps.data)"
                                    />
                                </template>
                            </Column>
                            <template #empty>
                                {{ $t('zones.empty') }}
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>

            <ZoneEditModal
                v-model:visible="editModal"
                :model="selectedModel"
            />

            <DeleteDialog
                ref="deleteDialog"
                v-model:visible="deleteDialog"
                :loading="deletingModel"
                @delete="onDelete"
            />

            <Dialog
                :header="$t('zones.importKmlFile')"
                v-model:visible="importDialog"
                :closable="false"
                modal
            >
                <FileUpload
                    mode="basic"
                    :maxFileSize="1000000"
                    :custom-upload="true"
                    @select="onUpload"
                />
                <small v-if="importForm.errors.file" class="p-invalid">
                    {{ importForm.errors.file }}
                </small>

                <template #footer>
                    <Button
                        :label="$t('common.cancel')"
                        @click="closeImportDialog"
                        class="p-button-text"
                    />
                    <Button
                        :label="$t('common.import')"
                        :loading="importForm.processing"
                        @click="importKML"
                    />
                </template>
            </Dialog>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import DeleteDialog from "../../Components/DeleteDialog";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import FileUpload from "primevue/fileupload";
import MapWithPolygons from "../../Components/MapWithPolygons";
import FlashMessage from "../../Services/FlashMessage";
import ZoneEditModal from "../../Components/ZoneEditModal";

export default {
    name: "Index",
    components: {
        ZoneEditModal,
        MapWithPolygons,
        AppLayout,
        Menubar,
        DataTable,
        Column,
        DeleteDialog,
        Button,
        Dialog,
        FileUpload
    },
    mixins: [FlashMessage],
    props: {
        zones: {
            type: Array,
            default: null,
        },
        mapCenter: {
            type: Object,
            default: {lat: 0, lng: 0}
        },
        colors: {
            type: Array,
            default: []
        }
    },
    data() {
        return {
            menuItems: [
                {
                    label: this.$t('zones.uploadZones'),
                    icon: 'pi pi-fw pi-file',
                    command: () => {
                        this.openImportDialog();
                    },
                },
                {
                    label: this.$t('zones.editor'),
                    icon: 'pi pi-fw pi-pencil',
                    command: () => {
                        this.$inertia.get(this.route('zones.editor'));
                    },
                },
                {
                    label: this.$t('zones.clearZones'),
                    icon: 'pi pi-fw pi-trash',
                    command: () => {
                        this.showDeleteDialog();
                    },
                },
            ],
            selectedModel: null,
            editModal: false,
            deleteDialog: false,
            deletingModel: false,
            importDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
        }
    },
    methods: {
        edit(model) {
            this.selectedModel = model;
            this.editModal = true;
        },
        onDelete() {
            this.deletingModel = true;
            if (!this.selectedModel) {
                this.$inertia.delete(this.route('zones.bulk-destroy'), {
                    onSuccess: () => {
                        this.deletingModel = false;
                        this.deleteDialog = false;
                        this.flashSuccess(this.$t('zones.zonesDeleted'));
                        this.$refs.deleteDialog.onClose();
                    }
                })
            } else {
                this.$inertia.delete(this.route('zones.destroy', this.selectedModel.id), {
                    onSuccess: () => {
                        this.deletingModel = false;
                        this.deleteDialog = false;
                        this.selectedModel = null;
                        this.$refs.deleteDialog.onClose();
                        this.flashSuccess(this.$t('zones.zoneDeleted'));
                        this.loadLazyData();
                    }
                })
            }
        },
        showDeleteDialog(model = null) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        openImportDialog() {
            this.importDialog = true;
        },
        onUpload(event) {
            if (event.files && event.files[0]) {
                this.importForm.file = event.files[0];
            }
        },
        importKML() {
            this.importForm.post(this.route('zones.store'), {
                onSuccess: () => {
                    this.importDialog = false;
                    this.flashSuccess(this.$t('zones.zonesAdded'));
                    this.closeImportDialog();
                }
            })
        },
        closeImportDialog() {
            this.importDialog = false;
        },
    }
}
</script>

<style scoped>

</style>
