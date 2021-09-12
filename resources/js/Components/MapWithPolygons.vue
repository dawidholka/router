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
    data() {
        return {
            mapCenter: null
        }
    },
    async mounted() {
        try {
            const google = await GoogleMapsService();
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
                const coords = JSON.parse(polygon.coords);

                if (coords?.outer) {
                    const paths = [];
                    for (let i = 0; i < coords.outer.length; i++) {
                        if (i === 0) {
                            this.mapCenter = {
                                lat: parseFloat(coords.outer[i][0]),
                                lng: parseFloat(coords.outer[i][1])
                            };
                        }

                        paths.push({
                            lat: parseFloat(coords.outer[i][0]),
                            lng: parseFloat(coords.outer[i][1])
                        });
                    }

                    polygons.push(new google.maps.Polygon({
                        paths: paths,
                        strokeColor: polygon.color,
                        strokeOpacity: 0.8,
                        strokeWidth: 2,
                        fillColor: polygon.color,
                        fillOpacity: 0.35
                    }));
                }
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
