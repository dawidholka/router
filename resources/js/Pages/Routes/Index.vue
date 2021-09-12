<template>
    <div>
        <AppLayout>
            <div class="p-grid">
                <div class="p-col-12">
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
                                        icon="pi pi-pencil"
                                        class="p-button-success p-button-sm mr-1"
                                        @click="edit(slotProps.data.id)"
                                    />
                                    <Button icon="pi pi-trash" class="p-button-sm p-button-danger"
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

export default {
    name: "Index",
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
        DeleteDialog,
        Button
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
                    label: 'Utwórz trasę',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('routes.create'));
                    },
                },
            ],
            selectedModel: null,
            deleteDialog: false,
            deletingModel: false,
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
                    this.loadLazyData();
                    this.$refs.deleteDialog.onClose();
                }
            })
        },
        showDeleteDialog(model) {
            this.selectedModel = model;
            this.deleteDialog = true;
        }
    }
}
</script>

<style scoped>

</style>
