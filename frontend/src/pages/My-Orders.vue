<template>
  <div class="column" :style="{ height: '800px' }">
    <div class="col col-md-10">
      <div class="row justify-center">
        <div
          v-if="
            ordersState.fetched === true &&
            ordersState.fetching === false &&
            ordersState.data.length > 0
          "
          class="col-md-10 offset-md-1 q-mt-xl"
        >
          <div class="row">
            <OrderCart :ordersState="ordersState"> </OrderCart>
          </div>
        </div>
        <div class="q-mt-lg" v-if="ordersState.fetching === true">
          <q-spinner color="primary" size="3em" :thickness="10" />
        </div>
        <div
          v-if="ordersState.data.length === 0 && ordersState.fetching === false"
          class="row"
        >
          <h4>Order Does Not Exist</h4>
        </div>
      </div>
    </div>
  </div>
</template>
<style>
.my-card {
  width: 100%;
  max-width: 250px;
}
</style>
<script lang="ts">
import { defineComponent, onMounted, ref } from "vue";
import { useRoute, useRouter, Router } from "vue-router";
import useOrder from "src/hooks/order/index";
import OrderCart from "../components/OrderCart.vue";
import { matShoppingBasket } from "@quasar/extras/material-icons";
export default defineComponent({
  name: "ProductsPage",
  components: { OrderCart },
  setup(props) {
    const route: any = useRoute();
    const pageParam = parseInt(route.params.page);
    const router: Router = useRouter();
    const { findOrdersByUserId, ordersState } = useOrder();
    console.log("path=>", route.name);
    onMounted(() => {
      findOrdersByUserId();
    });
    const calculateTotalPrice = (array: any[]) => {
      return array
        .map((item) => item.price)
        .reduce((acc: any, value: any) => {
          return acc + value;
        }, 0);
    };
    return {
      ordersState,
      pageId: route.params.id,
      matShoppingBasket,
      router,
      pageParam,
      current: ref(3),
      calculateTotalPrice,
    };
  },
});
</script>
