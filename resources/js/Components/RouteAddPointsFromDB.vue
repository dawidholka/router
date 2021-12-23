<template>
    <div class="card">
        <DataTable
            ref="dt"
            class="p-datatable-sm"
            :value="datatable.data"
            :lazy="true"
            data-key="id"
            :paginator="false"
            :rows="10"
            :loading="datatable.loading"
            :total-records="datatable.totalRecords"
            v-model:filters="datatable.filters"
            paginator-template="CurrentPageReport FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink RowsPerPageDropdown"
            :rows-per-page-options="[5,10,25]"
            :current-page-report-template="$tm('common.currentPageReportTemplate')"
            @page="onPage($event)"
            @sort="onSort($event)"
            @filter="onSort($event)"
        >
            <template #header>
                <div class="table-header">
                    <h5 class="m-0">
                        {{ $t('common.pointsSearch') }}
                    </h5>
                    <span class="p-input-icon-left">
                        <i class="pi pi-search"></i>
                        <InputText
                            v-model="datatable.filters['global'].value"
                            :placeholder="$t('common.search')"
                            style="width: 300px;"
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
            <Column field="name" :header="$t('common.name')" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.name }}
                </template>
            </Column>
            <Column field="city" :header="$t('common.address')" :sortable="true">
                <template #body="slotProps">
                    {{ slotProps.data.address }}
                </template>
            </Column>
            <Column field="lat" header="Geo" :sortable="true" style="width: 100px;">
                <template #body="slotProps">
                    {{ slotProps.data.geocoded ? $t('common.yes') : $t('common.no') }}
                </template>
            </Column>
            <Column :header="$t('common.options')" style="width: 150px;">
                <template #body="slotProps">
                    <Button
                        :label="$t('points.add')"
                        :loading="addingPoint === slotProps.data.id"
                        class="p-button-info p-button-sm mr-1"
                        @click="addPointToRoute(slotProps.data.id)"
                    />
                </template>
            </Column>
            <template #empty>
                {{ $t('common.searchEmpty') }}
            </template>
        </DataTable>
    </div>
</template>

<script>
import DataTable from "primevue/datatable";
import Column from "primevue/column";
import {FilterMatchMode} from "primevue/api";
import DatatableService from "../Services/DatatableService";
import InputText from "primevue/inputtext";
import Button from "primevue/button";
import axios from "axios";
import FlashMessage from "../Services/FlashMessage";

export default {
    name: "RouteAddPointsFromDB",
    components: {
        DataTable,
        Column,
        InputText,
        Button
    },
    emits: ['pointAdded'],
    mixins: [FlashMessage],
    props: {
        routeId: {
            type: Number,
            required: true,
        }
    },
    data() {
        return {
            datatable: {
                loading: true,
                totalRecords: 0,
                data: null,
                filters: {
                    'global': {value: null, matchMode: FilterMatchMode.CONTAINS},
                },
                lazyParams: {}
            },
            addingPoint: null,
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
            this.datatableService.getData(this.route('points.search.datatable'), this.datatable.lazyParams).then(data => {
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
        addPointToRoute(pointId) {
            this.addingPoint = pointId;
            axios.post(this.route('routes.add-point', this.routeId), {
                point_id: pointId
            }).then(() => {
                this.$emit('pointAdded');
                this.flashSuccess(this.$t('routes.waypointAdded'));
                this.addingPoint = null;
            }).catch(() => {
                this.addingPoint = null;
            });
        }
    }
}
</script>

<style scoped>

</style>
