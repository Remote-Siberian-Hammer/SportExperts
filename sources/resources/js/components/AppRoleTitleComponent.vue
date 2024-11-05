<script>
    export default {
        props: {
            userProps: Object
        }
    }
</script>

<template>
    <section class="d-flex d-center">
        <div v-if="this.userProps"  class="text-center mt-3 mb-3">
            <h2>Профиль
                <span v-if="this.userProps !== null && this.userProps.role === 'admin'">Администратора</span>
                <span v-if="this.userProps !== null && this.userProps.role === 'athlete'">Спортсмена</span></h2>
        </div>
    </section>
</template>
