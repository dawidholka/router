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
                            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
                            :rows-per-page-options="[5,10,25]"
                            current-page-report-template="Wyświetlanie od {first} do {last} z {totalRecords} wniosków"
                            @page="onPage($event)"
                            @sort="onSort($event)"
                            @filter="onSort($event)"
                        >
                            <template #header>
                                <div class="table-header">
                                    <h5 class="p-m-0">
                                        Użytkownicy
                                    </h5>
                                </div>
                            </template>
                            <Column field="id" header="ID" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.id }}
                                </template>
                            </Column>
                            <Column field="name" header="Nazwa" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.name }}
                                </template>
                            </Column>
                            <Column field="email" header="E-mail" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.email }}
                                </template>
                            </Column>
                            <Column field="admin" header="Admin" :sortable="true">
                                <template #body="slotProps">
                                    {{ slotProps.data.admin }}
                                </template>
                            </Column>
                            <template #empty>
                                Brak dodanych użytkowników.
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
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

export default {
    name: "Index",
    components: {
        AppLayout,
        Menubar,
        DataTable,
        Column,
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
                    label: 'Dodaj użytkownika',
                    icon: 'pi pi-fw pi-plus',
                    command: () => {
                        this.$inertia.get(this.route('users.create'));
                    },
                },
            ],
            importDialog: false,
            importForm: this.$inertia.form({
                file: null
            }),
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
            this.datatableService.getData(this.route('users.datatable'), this.datatable.lazyParams).then(data => {
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
    }
}
</script>

<style scoped>

</style>
