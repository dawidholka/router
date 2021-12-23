<template>
    <app-layout>
        <div>
            <div class="grid">
                <div v-if="$page.props.jetstream.canUpdateProfileInformation" class="col-12">
                    <update-profile-information-form :user="$page.props.user"/>
                </div>

                <div v-if="$page.props.jetstream.canUpdatePassword" class="col-12">
                    <update-password-form/>
                </div>

                <div class="col-12">
                    <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0"/>
                </div>
            </div>
        </div>
    </app-layout>
</template>

<script>
import AppLayout from '@/Layouts/AppLayout.vue'
import LogoutOtherBrowserSessionsForm from '@/Pages/Profile/Partials/LogoutOtherBrowserSessionsForm.vue'
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm.vue'
import UpdateProfileInformationForm from '@/Pages/Profile/Partials/UpdateProfileInformationForm.vue'

export default {
    props: ['sessions'],

    components: {
        AppLayout,
        LogoutOtherBrowserSessionsForm,
        UpdatePasswordForm,
        UpdateProfileInformationForm,
    },
}
</script>
