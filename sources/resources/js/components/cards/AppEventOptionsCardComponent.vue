<script>
    import Card from "primevue/card";

    export default {
        components: {
            Card
        },
        props: {
            optionsProps: Array
        }
    }
</script>

<template>
    <div class="mt-3">
        <Card>
            <template #header>
                <h4 class="text-center">ПАРАМЕТРЫ</h4>
            </template>
            <template #content>
                <section>
                    <section v-for="option in this.optionsProps"
                             :key="option.id"
                             class="option-list">
                        <div class="mb-3">
                            <strong>
                                <a href="#" class="text-link">{{ option.name }}</a>
                            </strong><br>
                            <span>{{ option.value }}</span>
                        </div>
                    </section>
                </section>
            </template>
        </Card>
    </div>
</template>
