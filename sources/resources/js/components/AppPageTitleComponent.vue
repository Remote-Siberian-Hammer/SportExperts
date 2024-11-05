<script>
    import Button from 'primevue/button';

    export default {
        props: {
            title: String,
            subTitle: String,
            baseUrl: String,
            endPoint: String,
        },
        components: {
            Button
        }
    }
</script>

<template>
    <div class="text-center">
        <h2>{{ this.title }}</h2>
        <a v-if="this.baseUrl && this.endPoint"
           :href="this.baseUrl + this.endPoint">
            <Button :label="this.subTitle"
                    severity="info"
                    link />
        </a>
    </div>
</template>
