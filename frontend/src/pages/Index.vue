<template>
  <div class="q-pa-md">
    <q-carousel
      animated
      v-model="slide"
      navigation
      infinite
      :autoplay="autoplay"
      arrows
      transition-prev="slide-right"
      transition-next="slide-left"
      @mouseenter="autoplay = false"
      @mouseleave="autoplay = true"
    >
      <q-carousel-slide
        :name="1"
        img-src="https://cdn.quasar.dev/img/mountains.jpg"
      />
      <q-carousel-slide
        :name="2"
        img-src="https://cdn.quasar.dev/img/parallax1.jpg"
      />
      <q-carousel-slide
        :name="3"
        img-src="https://cdn.quasar.dev/img/parallax2.jpg"
      />
      <q-carousel-slide
        :name="4"
        img-src="https://cdn.quasar.dev/img/quasar.jpg"
      />
    </q-carousel>
  </div>
</template>

<script lang="ts">
import { Todo, Meta } from "components/models";
import ExampleComponent from "components/CompositionComponent.vue";
import { defineComponent, ref } from "vue";

export default defineComponent({
  name: "PageIndex",
  components: { ExampleComponent },
  setup() {
    const todos = ref<Todo[]>([
      {
        id: 1,
        content: "ct1",
      },
      {
        id: 2,
        content: "ct2",
      },
      {
        id: 3,
        content: "ct3",
      },
      {
        id: 4,
        content: "ct4",
      },
      {
        id: 5,
        content: "ct5",
      },
    ]);
    const meta = ref<Meta>({
      totalCount: 1200,
    });
    console.log(todos.value);
    return { todos, meta, slide: ref(1), autoplay: ref(true) };
  },
});
</script>
