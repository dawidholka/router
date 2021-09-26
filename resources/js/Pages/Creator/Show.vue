<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-12">
                    <div class="card">
                        <h5>Kreator tras</h5>
                        <div class="mb-5">
                            <FileUpload
                                mode="basic"
                                class="mb-3"
                                :maxFileSize="10000000"
                                :custom-upload="true"
                                @select="onUpload"
                            />
                            <small v-if="form.errors.file" class="p-invalid">
                                {{ form.errors.file }}
                            </small>
                        </div>
                        <Button
                            label="Stwórz trasy"
                            :loading="form.processing"
                            @click="submit"
                        />
                    </div>
                </div>
            </div>
        </AppLayout>
    </div>
</template>

<script>
import AppLayout from "../../Layouts/AppLayout";
import FileUpload from "primevue/fileupload";
import Button from "primevue/button";

export default {
    name: "Creator",
    components: {
        AppLayout,
        FileUpload,
        Button
    },
    data() {
        return {
            form: this.$inertia.form({
                file: null
            })
        }
    },
    methods: {
        onUpload(event) {
            if (event.files && event.files[0]) {
                this.form.file = event.files[0];
            }
        },
        submit() {
            this.form.post(this.route('creator'), {
                onSuccess: () => {

                }
            })
        }
    }
}
</script>

<style scoped>

</style>
