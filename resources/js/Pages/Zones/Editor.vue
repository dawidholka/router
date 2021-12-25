<template>
    <div>
        <AppLayout>
            <div style="width:100%; display: flex; justify-content: center;">
                <span style="width: auto;" />
                {{ mapMode }}
                <span style="width: auto;" />
                <button @click="mapMode='edit'">Edit</button>
            </div>
            <google-map
                :center="mapCenter"
                ref="mapRef"
                api-key="AIzaSyBgzEBbI-IBz9oVwmIoRdGNttbSZ0V1foE"
                :zoom="7"
                map-type-id="roadmap"
                style="width: 100%; height: 800px; display:flex; justify-content: center; align-items: flex-start;"
                :options="{
                    zoomControl: true,
                    mapTypeControl: false,
                    scaleControl: false,
                    streetViewControl: false,
                    rotateControl: false,
                    fullscreenControl: false,
                    disableDefaultUi: false,
                    draggable: mapDraggable,
                    draggableCursor: mapCursor
                  }"
            >
                <template #visible>
                    <drawing-manager
                        v-if="mapMode==='edit'"
                        :rectangle-options="rectangleOptions"
                        :circle-options="circleOptions"
                        :polyline-options="polylineOptions"
                        :polygon-options="polygonOptions"
                        :shapes="shapes"
                    >
                        <template v-slot="on">
                            <drawing-toolbar
                                @drawingmode_changed="on.setDrawingMode($event)"
                                @delete_selection="on.deleteSelection()"
                                @save="mapMode='ready'"
                            />
                        </template>
                    </drawing-manager>
                </template>
            </google-map>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import GoogleMap from "../../Components/GoogleMaps/GoogleMap";
import DrawingManager from "../../Components/GoogleMaps/DrawingManager";

const toolbarTemplate =
    '<div style="background-color: #040404; display: flex; position: absolute; padding: 8px">' +
    "  <div><button @click=\"$emit('drawingmode_changed', 'rectangle')\">Rectangle</button></div>" +
    "  <div><button @click=\"$emit('drawingmode_changed', 'circle')\">Circle</button></div>" +
    "  <div><button @click=\"$emit('drawingmode_changed', 'polyline')\">Line</button></div>" +
    "  <div><button @click=\"$emit('drawingmode_changed', 'polygon')\">Polygon</button></div>" +
    "  <div><button @click=\"$emit('delete_selection')\">Delete</button></div>" +
    "  <div><button @click=\"$emit('save')\">Save</button></div>" +
    "</div>";

export default {
    name: "Create",
    components: {DrawingManager, GoogleMap, AppLayout,
        drawingToolbar: {
            template: toolbarTemplate
        }
    },
    data() {
        return {
            mapCenter: {lat: 4.5, lng: 99},
            mapMode: null,
            mapDraggable: true,
            mapCursor: null,
            shapes: [],
            rectangleOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            circleOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            polylineOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            },
            polygonOptions: {
                fillColor: '#777',
                fillOpacity: 0.4,
                strokeWeight: 2,
                strokeColor: '#999',
                draggable: false,
                editable: false,
                clickable: true
            }
        }
    },
    watch: {
        mapMode(newMode, oldMode) {
            if (newMode === 'ready') {
                if (oldMode === 'edit') {
                    this.mapDraggable = true;
                    this.mapCursor = null;
                    return;
                }
            }

            if (newMode === 'edit') {
                this.mapDraggable = false;
                this.mapCursor = 'default';
            }
        }
    },
    mounted() {
        this.mapMode = 'ready';
    }
}
</script>

<style scoped>

</style>
