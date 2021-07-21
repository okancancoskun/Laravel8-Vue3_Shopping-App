<template>
  <div
    class="col-lg-12 col-xs-12 q-mt-lg"
    v-for="(item, index) in ordersState.data"
    :key="index"
  >
    <q-card flat bordered class="order-card">
      <q-card-section>
        <div class="text-h6">
          Order id: <span>{{ item.id }}</span>
        </div>
      </q-card-section>
      <q-separator inset />
      <div v-for="(itm, ix) in item.order_items" :key="ix">
        <q-card-section>
          <div class="row">
            <div class="col-md-4">
              <b>Product name:</b> {{ itm.product_id.name }}
              <q-separator vertical />
            </div>

            <div class="col-md-4"><b>Quantity:</b> {{ itm.quantity }}</div>
            <div class="col-md-4"><b>Price:</b> {{ itm.price }}</div>
          </div>
        </q-card-section>
        <q-separator inset />
      </div>
      <q-card-section>
        <div class="row">
          <div class="col-md-6">
            <b>Total Price: </b>{{ calculateTotalPrice(item.order_items) }}
          </div>
          <div class="col-md-6">
            <b>Total Quantity: </b>
            {{ calculateTotalQuantity(item.order_items) }}
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script lang="ts">
import { IOrdersState } from "../hooks/order/index";
import { defineComponent, PropType } from "vue";
export default defineComponent({
  name: "OrderCartComponent",
  props: {
    ordersState: {
      type: Object as PropType<IOrdersState>,
      required: true,
    },
  },
  setup(props) {
    const calculateTotalPrice = (array: any[]) => {
      return array
        .map((item) => item.price)
        .reduce((acc: any, value: any) => {
          return acc + value;
        }, 0);
    };
    const calculateTotalQuantity = (array: any[]) => {
      let sum: number = 0;
      array.forEach((item) => (sum += item.quantity));
      return sum;
    };
    return { calculateTotalPrice, calculateTotalQuantity };
  },
});
</script>

<style>
.my-card a {
  text-decoration: none;
  color: white;
}
.order-card {
  width: 800px;
}
</style>
