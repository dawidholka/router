<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5>{{ $t('creator.title') }}</h5>

                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="date">{{ $t('common.date') }}</label>
                                <Calendar
                                    id="date"
                                    class="w-full"
                                    v-model="form.date"
                                    date-format="yy-mm-dd"
                                    :manual-input="false"
                                    :class="{'p-invalid': form.errors.date}"
                                />
                                <small v-if="form.errors.date" class="p-invalid">
                                    {{ form.errors.date }}
                                </small>
                            </div>
                        </div>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="file">{{ $t('common.file') }}</label>
                                <FileUpload
                                    id="file"
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
                        </div>
                        <Button
                            :label="$t('creator.createRoutes')"
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
import Calendar from "primevue/calendar";

export default {
    name: "Creator",
    components: {
        AppLayout,
        FileUpload,
        Button,
        Calendar
    },
    data() {
        return {
            form: this.$inertia.form({
                date: new Date(),
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
