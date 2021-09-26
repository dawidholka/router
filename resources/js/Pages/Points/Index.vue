<template>
    <div>
        <AppLayout>
            <div class="p-grid">
                <div v-if="$page.props.admin" class="p-col-12">
                    <div class="card">
                        <Menubar :model="menuItems"/>
                    </div>
                </div>
                <div class="p-col-12">
                    <div class="card">
                        <DataTable
                            ref="dt"
                            :value="datatable.data"
                            :lazy="true"
                            data-key="id"
                            :paginator="true"
                            :rows="10"
                            :loading="datatable.loading"
                            :total-records="datatable.totalRecords"
                            v-model:filters="datatable.filters"
                            :globalFilterFields="['status']"
                            filterDisplay="row"
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[5,10,25]"
                            current-page-report-template="Wyświetlanie od {first} do {last} z {totalRecords} elementów"
                            @page="onPage($event)"
                            @sort="onSort($event)"
                            @filter="onSort($event)"
                        >
                            <template #header>
                                <div class="table-header">
                                    <h5 class="m-0">
                                        Punkty
                                    </h5>
                                    <span class="p-input-icon-left">
                                    <i class="pi pi-search"></i>
                                    <InputText
                                        v-model="datatable.filters['global'].value"
                                        placeholder="Szukaj..."
                                        @keydown.enter="loadLazyData"
                                    />
                                </span>
                                </div>
                            </template>
                            <Column field="id" header="ID" :sortable="true" style="width: 150px;">
                                <template #body="slotProps">
                                    {{ slotProps.data.id }}
                                </template>
                            </Column>
                            <Column field="name" header="Nazwa" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.name }}
                                </template>
                            </Column>
                            <Column field="city" header="Adres" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.address }}
                                </template>
                            </Column>
                            <Column field="lat" header="Geo" :sortable="true" style="width: 100px;">
                                <template #body="slotProps">
                                    {{ slotProps.data.geocoded ? 'Tak' : 'Nie' }}
                                </template>
                            </Column>
                            <Column header="Opcje" style="width: 150px;">
                                <template #body="slotProps">
                                    <Button
                                        icon="pi pi-eye"
                                        class="p-button-info p-button-sm mr-1"
                                        @click="show(slotProps.data.id)"
                                    />
                                    <Button
                                        v-if="$page.props.admin"
                                        icon="pi pi-pencil"
                                        class="p-button-success p-button-sm mr-1"
                                        @click="edit(slotProps.data.id)"
                                    />
                                    <Button
                                        v-if="$page.props.admin"
                                        icon="pi pi-trash" class="p-button-sm p-button-danger"
                                            @click="showDeleteDialog(slotProps.data)"
                                    />
                                </template>
                            </Column>
                            <template #empty>
                                Brak dodanych punktów.
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>

            <DeleteDialog
                ref="deleteDialog"
                v-model:visible="deleteDialog"
                :loading="deletingModel"
                @delete="onDelete"
            />

            <DeleteDialog
                ref="bulkGeocodeDialog"
                v-model:visible="bulkGeocodeModal"
                :loading="bulkGeocoding"
                @delete="onBulkGeocode"
            />

            <Dialog
                v-model:visible="bulkDeleteModal"
                :style="{width: '450px'}"
                header="Czyszczenie punktów"
                :modal="true"
                :closable="false"
            >
                <div class="field">
                    <label for="bulk-delete">
                        Wyczyść dane aplikacji
                    </label>
                    <Dropdown
                        id="bulk-delete"
                        class="w-full"
                        :options="bulkDeleteOptions"
                        option-value="value"
                        option-label="label"
                        v-model="bulkDeleteForm.option"
                        :class="{'p-invalid': bulkDeleteForm.errors.option}"
                    />
                    <small v-if="bulkDeleteForm.errors.option" class="p-invalid">
                        {{ bulkDeleteForm.errors.option }}
                    </small>
                </div>
                <template #footer>
                    <Button
                        label="Anuluj"
                        icon="pi pi-times"
                        class="p-button-text"
                        @click="bulkDeleteModal = false"
                    />
                    <Button
                        label="Zapisz"
                        icon="pi pi-check"
                        class="p-button-text"
                        :loading="bulkDeleteForm.processing"
                        @click="bulkDelete"
                    />
                </template>
            </Dialog>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import DataTable from "primevue/datatable";
import {FilterMatchMode} from "primevue/api";
import DatatableService from "../../Services/DatatableService";
import Menubar from "primevue/menubar";
import Column from "primevue/column";
import Button from "primevue/button";
import DeleteDialog from "../../Components/DeleteDialog";
import InputText from "primevue/inputtext";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";

export default {
    name: "Index",
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        Button,
        DeleteDialog,
        InputText,
        Dialog,
        Dropdown
    },
    data() {
        return {
            datatable: {
                loading: true,
                totalRecords: 0,
                data: null,
                filters: {
                    'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
                    'status': {value: null, matchMode: FilterMatchMode.EQUALS},
                },
                lazyParams: {}
            },
            menuItems: [
                {
                    label: 'Dodaj punkt',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('points.create'));
                    },
                },
                {
                    label: 'Geolokalizuj',
                    icon: 'pi pi-fw pi-map',
                    command: () => {
                        this.bulkGeocodeModal = true;
                        this.bulkGeocoding = false;
                    },
                },
                {
                    label: 'Wyczyść punkty',
                    icon: 'pi pi-fw pi-trash',
                    command: () => {
                        this.openBulkDeleteModal();
                    },
                },
            ],
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
            bulkGeocodeModal: false,
            bulkGeocoding: false,
            bulkDeleteModal: false,
            bulkDeleteForm: this.$inertia.form({
                option: null
            }),
            bulkDeleteOptions: [
                {value: 'all', label: 'Wszystkie'},
                {value: 'last-hour', label: 'Z ostatniej godziny'},
                {value: 'older-then-30-days', label: 'Starsze niż 30 dni'},
                {value: 'older-then-90-days', label: 'Starsze niż 90 dni'},
            ],
        }
    },
    datatableService: null,
    created() {
        this.datatableService = new DatatableService();
    },
    mounted() {
        this.datatable.loading = true;
        this.datatable.lazyParams = {
            first: 0,
            rows: this.$refs.dt.rows,
            sortField: 'id',
            sortOrder: -1,
            filters: this.datatable.filters
        };
        this.loadLazyData();
    },
    methods: {
        loadLazyData() {
            this.datatable.loading = true;
            this.datatableService.getData(this.route('points.datatable'), this.datatable.lazyParams).then(data => {
                this.datatable.data = data.data;
                this.datatable.totalRecords = data.total;
                this.datatable.loading = false;
            });
        },
        onPage(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        onSort(event) {
            this.datatable.lazyParams = event;
            this.loadLazyData();
        },
        show(id) {
            this.$inertia.get(this.route('points.show', id));
        },
        edit(id) {
            this.$inertia.get(this.route('points.edit', id));
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('points.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
        onBulkGeocode()
        {
            this.bulkGeocoding = true;
            this.$inertia.post(this.route('points.bulk-geocode'),{},{
                onSuccess: () => {
                    this.bulkGeocoding = false;
                    this.bulkGeocodeModal = false;
                    this.loadLazyData();
                    this.$refs.bulkGeocodeDialog.onClose();
                }
            })
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        },
        openBulkDeleteModal() {
            this.bulkDeleteModal = true;
        },
        bulkDelete() {
            this.bulkDeleteForm.post(this.route('points.bulk-destroy'), {
                onSuccess: () => {
                    this.loadLazyData();
                    this.bulkDeleteModal = false;
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
