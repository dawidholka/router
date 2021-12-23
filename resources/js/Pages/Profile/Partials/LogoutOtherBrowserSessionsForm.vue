<template>
    <card>
        <template #title>
            {{ $t('profile.browserSessions') }}
        </template>

        <template #content>
            <div class="mb-3">
                {{ $t('profile.manageSessions') }}
            </div>

            <div class="max-w-xl text-sm text-gray-600">
                {{ $t('profile.manageSessionsInfo') }}
            </div>

            <!-- Other Browser Sessions -->
            <div class="mt-5 space-y-6" v-if="sessions.length > 0">
                <div class="flex items-center" v-for="(session, i) in sessions" :key="i">
                    <div>
                        <svg fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                             viewBox="0 0 24 24" stroke="currentColor" class="w-2rem h-2rem text-gray-500"
                             v-if="session.agent.is_desktop">
                            <path
                                d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke-width="2"
                             stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"
                             class="w-8 h-8 text-gray-500" v-else>
                            <path d="M0 0h24v24H0z" stroke="none"></path>
                            <rect x="7" y="4" width="10" height="16" rx="1"></rect>
                            <path d="M11 5h2M12 17v.01"></path>
                        </svg>
                    </div>

                    <div class="ml-3">
                        <div class="text-sm text-gray-600">
                            {{ session.agent.platform }} - {{ session.agent.browser }}
                        </div>

                        <div>
                            <div class="text-xs text-gray-500">
                                {{ session.ip_address }},

                                <span class="text-green-500 font-semibold"
                                      v-if="session.is_current_device">{{ $t('profile.currentDevice') }}</span>
                                <span v-else>{{ $t('profile.lastActivity') }} {{ session.last_active }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Log Out Other Devices Confirmation Modal -->
        </template>

        <template #footer>
            <div class="flex items-center mt-5">
                <Button
                    @click="confirmLogout"
                    :label="$t('profile.logoutOtherBrowserSessions')"
                />
            </div>
        </template>

        <Dialog
            :visible="confirmingLogout"
            :closable="false"
            :modal="true"
            :header="$t('profile.logoutOtherBrowserSessions')"
            @close="closeModal"
        >

            {{ $t('profile.logoutOtherBrowserSessionsConfirm') }}

            <div class="field">
                <label for="password">{{ $t('common.password') }}</label>
                <InputText
                    id="password"
                    class="w-full"
                    :class="{'p-invalid': form.errors.password}"
                    type="password"
                    v-model="form.password"
                />
                <small v-if="form.errors.password" class="p-invalid">
                    {{ form.errors.password }}
                </small>
            </div>

            <template #footer>
                <Button
                    :label="$t('common.cancel')"
                    icon="pi pi-times"
                    class="p-button-text"
                    @click="closeModal"
                />
                <Button
                    :label="$t('auth.signOut')"
                    icon="pi pi-check"
                    class="p-button-text"
                    :loading="form.processing"
                    @click="logoutOtherBrowserSessions"
                />
            </template>
        </Dialog>
    </card>
</template>

<script>
import Card from "primevue/card";
import Button from "primevue/button";
import Dialog from "primevue/dialog";
import InputText from "primevue/inputtext";
import FlashMessage from "../../../Services/FlashMessage";

export default {
    props: ['sessions'],

    components: {
        Card,
        Button,
        Dialog,
        InputText,
    },
    mixins: [FlashMessage],
    data() {
        return {
            confirmingLogout: false,

            form: this.$inertia.form({
                password: '',
            })
        }
    },

    methods: {
        confirmLogout() {
            this.confirmingLogout = true

            setTimeout(() => this.$refs.password.focus(), 250)
        },

        logoutOtherBrowserSessions() {
            this.form.delete(this.route('other-browser-sessions.destroy'), {
                preserveScroll: true,
                onSuccess: () => {
                    this.closeModal();
                    this.flashSuccess(this.$t('profile.loggedOutOtherBrowserSessions'))
                },
                onFinish: () => this.form.reset(),
            })
        },

        closeModal() {
            this.confirmingLogout = false
            this.form.clearErrors();
            this.form.reset()
        },
    },
}
</script>
