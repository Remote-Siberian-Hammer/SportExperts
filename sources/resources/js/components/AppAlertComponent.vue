<script>
    import Message from 'primevue/message';

    export default {
        props: {
            messageSuccess: String,
            messageError: String,
        },
        components: {
            Message
        }
    }
</script>

<template>
    <section class="mt-1 mb-2" v-if="this.messageSuccess">
        <Message severity="success">{{ this.messageSuccess }}</Message>
    </section>
    <section class="mt-1 mb-2" v-if="this.messageError">
        <Message severity="error">{{ this.messageError }}</Message>
    </section>
</template>
