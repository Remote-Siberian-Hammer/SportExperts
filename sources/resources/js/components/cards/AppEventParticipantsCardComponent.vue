<script>
import Card from "primevue/card";
import {WEB_URL} from "../../common/route/web";

export default {
    data() {
      return {
          baseUrl: WEB_URL
      }
    },
    components: {
        Card
    },
    props: {
        participantsProps: Array,
        eventKeyProps: String
    }
}
</script>

<template>
    <div class="mt-3">
        <Card>
            <template #header>
                <h4 class="text-center">СПИСКИ</h4>
            </template>
            <template #content>
                <section>
                    <section v-for="participant in this.participantsProps"
                             :key="participant.id"
                             class="option-list">
                        <div class="mb-3">
                            <strong>
                                <a :href="this.baseUrl + 'participant/search?event=' + this.eventKeyProps" class="text-link">
                                    {{ participant.first_name }} {{ participant.last_name }}
                                </a><br>
                            </strong>
                        </div>
                    </section>
                </section>
            </template>
        </Card>
    </div>
</template>
