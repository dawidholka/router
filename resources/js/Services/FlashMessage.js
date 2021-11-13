const FlashMessage = {
    methods: {
        flashSuccess(message) {
            this.$toast.add({
                severity: 'success',
                summary: 'Success',
                detail: message,
                life: 3000
            });
        },
        flashError(message) {
            this.$toast.add({
                severity: 'error',
                summary: 'Error',
                detail: message,
                life: 3000
            });
        }
    }
};

export default FlashMessage;
