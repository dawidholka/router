<template>
    <div class="map"/>
</template>

<script>
import GoogleMapsService from '../Services/GoogleMapsService'

export default {
    name: "MapWithPolygons",
    props: {
        apiKey: {
            type: String,
            required: true
        },
        polygons: {
            type: Array,
            required: true
        }
    },
    async mounted() {
        try {
            const google = await GoogleMapsService(this.apiKey);
            const polygons = this.getPolygons();
            const map = new google.maps.Map(this.$el, {
                zoom: 8,
                center: this.mapCenter,
                mapTypeControl: false,
                streetViewControl: false
            });

            polygons.map(polygon => {
                polygon.setMap(map);
            })
        } catch (error) {
            console.error(error);
        }
    },
    methods: {
        getPolygons() {
            const polygons = [];

            this.polygons.map(polygon => {
                polygons.push(new google.maps.Polygon({
                    paths: polygon.coords,
                    strokeColor: polygon.color,
                    strokeOpacity: 0.8,
                    strokeWidth: 2,
                    fillColor: polygon.color,
                    fillOpacity: 0.35
                }));
            });

            return polygons;
        }
    }
}
</script>

<style scoped>
.map {
    width: 100%;
    height: 500px;
}
</style>
