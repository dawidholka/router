<template>
    <div>
        <AppLayout>
            <div class="grid">
                <div class="col-6 col-offset-3">
                    <div class="card">
                        <h5 v-if="!pageUser">{{ $t('users.addUser') }}</h5>
                        <h5 v-else>{{ $t('users.editUser') }}</h5>
                        <RouterInputText
                            name="name"
                            :label="$t('common.name')"
                            v-model="form.name"
                            :error="form.errors.name"
                        />
                        <RouterInputText
                            name="login"
                            :label="$t('common.email')"
                            v-model="form.email"
                            :error="form.errors.email"
                        />
                        <RouterInputText
                            name="password"
                            :label="$t('common.password')"
                            type="password"
                            v-model="form.password"
                            :error="form.errors.password"
                        />
                        <div class="field">
                            <label for="admin">{{ $t('common.admin') }}</label>
                            <Dropdown
                                id="admin"
                                class="w-full"
                                :options="adminOptions"
                                option-value="value"
                                option-label="name"
                                :placeholder="$t('common.chooseOption')"
                                v-model="form.admin"
                                :class="{'p-invalid': form.errors.admin}"
                            />
                            <small v-if="form.errors.admin" class="p-invalid">
                                {{ form.errors.admin }}
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
                            :label="$t('common.submit')"
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
import Button from "primevue/button";
import InputText from "primevue/inputtext";
import Dropdown from "primevue/dropdown";
import FlashMessage from "../../Services/FlashMessage";
import RouterInputText from "../../Components/RouterInputText";

export default {
    name: "Create",
    components: {
        RouterInputText,
        AppLayout,
        Button,
        InputText,
        Dropdown,
    },
    mixins: [FlashMessage],
    props: {
        pageUser: {
            type: Object,
            default: null
        },
    },
    data() {
        return {
            form: this.$inertia.form({
                'name': this.pageUser?.name ?? null,
                'email': this.pageUser?.email ?? null,
                'password': null,
                'admin': this.pageUser?.admin ?? false,
            }),
            adminOptions: [
                {name: this.$t('common.no'), value: false},
                {name: this.$t('common.yes'), value: true}
            ]
        }
    },
    methods: {
        cancel() {
            this.$inertia.get(this.route('users.index'));
        },
        submit() {
            if (this.pageUser) {
                this.form.put(this.route('users.update', this.pageUser.id), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('users.userSaved'));
                    }
                })
            } else {
                this.form.post(this.route('users.store'), {
                    onSuccess: () => {
                        this.flashSuccess(this.$t('users.userAdded'));
                    }
                })
            }
        }
    }
}
</script>

<style scoped>

</style>
