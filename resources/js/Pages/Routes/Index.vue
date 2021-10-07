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
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[5,10,25]"
                            current-page-report-template="Wyświetlanie od {first} do {last} z {totalRecords} wniosków"
                            @page="onPage($event)"
                            @sort="onSort($event)"
                            @filter="onSort($event)"
                        >
                            <template #header>
                                <div class="table-header">
                                    <h5 class="m-0">
                                        Trasy
                                    </h5>
                                </div>
                            </template>
                            <Column field="id" header="ID" :sortable="true" style="width: 150px;">
                                <template #body="slotProps">
                                    {{ slotProps.data.id }}
                                </template>
                            </Column>
                            <Column field="delivery_time" header="Data" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.date }}
                                </template>
                            </Column>
                            <Column field="driver_id" header="Kierowca" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.driver ?? 'Brak' }}
                                </template>
                            </Column>
                            <Column field="note" header="Notatka" :sortable="false">
                                <template #body="slotProps">
                                    {{ slotProps.data.note }}
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
                                Brak dodanych tras.
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

            <Dialog
                v-model:visible="bulkDeleteModal"
                :style="{width: '450px'}"
                header="Czyszczenie tras"
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
import DeleteDialog from "../../Components/DeleteDialog";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import Dropdown from "primevue/dropdown";
import FlashMessage from "../../Services/FlashMessage";

export default {
    name: "Index",
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        DeleteDialog,
        Button,
        Dialog,
        Dropdown
    },
    mixins: [FlashMessage],
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
                    label: 'Utwórz trasę',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('routes.create'));
                    },
                },
                {
                    label: 'Wyczyść trasy',
                    icon: 'pi pi-fw pi-trash',
                    command: () => {
                        this.openBulkDeleteModal();
                    },
                },
            ],
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
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
            this.datatableService.getData(this.route('routes.datatable'), this.datatable.lazyParams).then(data => {
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
            this.$inertia.get(this.route('routes.show', id));
        },
        edit(id) {
            this.$inertia.get(this.route('routes.edit', id));
        },
        onDelete() {
            this.deletingModel = true;
            this.$inertia.delete(this.route('routes.destroy', this.selectedModel.id), {
                onSuccess: () => {
                    this.deletingModel = false;
                    this.deleteDialog = false;
                    this.flashSuccess('Usunięto trasę.');
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
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
            this.bulkDeleteForm.post(this.route('routes.bulk-destroy'), {
                onSuccess: () => {
                    this.loadLazyData();
                    this.flashSuccess('Wyczyszczono trasy.');
                    this.bulkDeleteModal = false;
                }
            });
        }
    }
}
</script>

<style scoped>

</style>
