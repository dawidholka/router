<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5>{{ $t('routes.createRoute') }}</h5>
                        <div class="formgrid grid">
                            <div class="field col">
                                <label for="delivery_date">{{ $t('common.date') }}</label>
                                <Calendar
                                    id="delivery_date"
                                    class="w-full"
                                    v-model="form.delivery_date"
                                    date-format="yy-mm-dd"
                                    :manual-input="false"
                                    :class="{'p-invalid': form.errors.delivery_date}"
                                />
                                <small v-if="form.errors.delivery_date" class="p-invalid">
                                    {{ form.errors.delivery_date }}
                                </small>
                            </div>
                            <div class="field col">
                                <label for="driver">{{ $t('common.driver') }}</label>
                                <Dropdown
                                    id="driver"
                                    class="w-full"
                                    v-model="form.driver"
                                    :options="drivers"
                                    option-label="name"
                                    option-value="id"
                                    :placeholder="$t('routes.selectDriver')"
                                />
                                <small v-if="form.errors.driver" class="p-invalid">
                                    {{ form.errors.driver }}
                                </small>
                            </div>
                        </div>
                        <div class="field">
                            <label for="note">{{ $t('common.note') }}</label>
                            <Textarea
                                id="note"
                                class="w-full"
                                rows="5"
                                v-model="form.note"
                            />
                            <small v-if="form.errors.note" class="p-invalid">
                                {{ form.errors.note }}
                            </small>
                        </div>
                    </div>
                    <div class="flex justify-content-end mt-3">
                        <Button
                            :label="$t('common.cancel')"
                            class="p-button-secondary p-button-outlined p-button-lg mr-3"
                            @click="cancel"
                        />
                        <Button
                            :label="$t('common.save')"
                            class="p-button-lg"
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
import Dropdown from "primevue/dropdown";
import Calendar from "primevue/calendar";
import Textarea from "primevue/textarea";
import Button from "primevue/button";
import FlashMessage from "../../Services/FlashMessage";

export default {
    name: "Create",
    components: {
        AppLayout,
        Dropdown,
        Calendar,
        Textarea,
        Button
    },
    mixins: [FlashMessage],
    props: {
        drivers: {
            type: Array,
            default: null
        }
    },
    data() {
        return {
            form: this.$inertia.form({
                'delivery_date': new Date(),
                'note': null,
                'driver': null
            })
        }
    },
    methods: {
        cancel() {
            this.$inertia.get(this.route('routes.index'));
        },
        submit() {
            this.form.post(this.route('routes.store'), {
                onSuccess: () => {
                    this.flashSuccess('Utworzono trasę.');
                }
            })
        }
    }
}
</script>

<style scoped>

</style>
