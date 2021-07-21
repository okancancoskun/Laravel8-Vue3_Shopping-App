<template>
  <div
    class="col-lg-3 col-xs-12 q-mt-lg-"
    v-for="prd in productsState.data.data"
    :key="prd.id"
  >
    <q-card class="my-card bg-secondary text-white">
      <router-link :to="{ path: `/product/detail/${prd.id}` }">
        <q-card-section>
          <div class="text-h6">{{ prd.name }}</div>
          <div class="text-subtitle2">{{ prd.detail }}</div>
        </q-card-section>
        <q-card-section> {{ prd.price }} $ </q-card-section>
      </router-link>

      <q-separator dark />
      <q-card-actions v-if="done">
        <q-btn @click="addToCart(prd.id)" flat>Add To Cart</q-btn>
      </q-card-actions>
    </q-card>
  </div>
</template>

<script lang="ts">
import { IState } from "../hooks/product";
import { defineComponent, PropType } from "vue";
import useLogin from "src/hooks/auth/userLogin";
import { IProduct } from "src/interfaces";
export default defineComponent({
  name: "ProductCartComponent",
  props: {
    productsState: {
      type: Object as PropType<IState>,
      required: true,
    },
    addToCart: {
      type: Function as PropType<Function>,
      required: true,
    },
    newData: {
      type: Array as PropType<IProduct[]>,
      default: () => [],
    },
  },
  setup(props) {
    const { done } = useLogin();
    return { done };
  },
});
</script>

<style>
.my-card a {
  text-decoration: none;
  color: white;
}
</style>
