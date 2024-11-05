<script>
    import Card from "primevue/card";
    import AppTournamentValuesComponent from "./AppTournamentValuesComponent.vue";
    import AppParticipantsCardComponent from "./cards/AppParticipantsCardComponent.vue";

    export default {
        data() {
          return {
              eventKey: null
          };
        },
        props: {
            eventKeyProps: String,
            eventStatusProps: String,
            tournamentProps: Object,
            roleProps: String,
        },
        components: {
            AppParticipantsCardComponent,
            Card,
            AppTournamentValuesComponent
        },
        methods: {
            addMessageError: async function (data) { await this.$emit('messageErrorEmit', data); },
        },
        watch: {
            eventKeyProps: {
                handler(value) {
                    this.eventKey = value;
                },
                immediate: true,
                deep: true
            }
        }
    }
</script>

<template>
    <section id="tid" class="d-flex">
        <section id="step-tournament" style="width: 17em;">
            <Card class="mb-3 w-100">
                <template #content>
                    <strong># {{ this.tournamentProps.stage }} ЭТАП</strong>
                </template>
            </Card>
        </section>
        <AppTournamentValuesComponent
            :eventStatusProps="this.eventStatusProps"
            :eventKeyProps="this.eventKeyProps"
            :tournamentValuesProps="this.tournamentProps.tournament_values"
            :roleProps="this.roleProps"
            :stageProps="this.tournamentProps.stage"
            @messageErrorEmit="addMessageError" />
    </section>
</template>
