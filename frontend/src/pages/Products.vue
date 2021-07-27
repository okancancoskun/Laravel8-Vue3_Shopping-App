<template>
  <div
    v-if="
      productsState.currentPage !== undefined &&
      productsState.lastPage !== undefined
    "
    class="column"
    :style="{ height: '800px' }"
  >
    <div class="col col-md-10">
      <div v-if="productsState.data.data.length === 0" class="row justify-center">
        <h3>Products Doest Not Exist</h3>
      </div>
      <div class="row justify-center">
        <div
          v-if="
            productsState.fetched === true && productsState.fetching === false
          "
          class="col-md-10 offset-md-1 q-mt-xl"
        >
          <div class="row">
            <product-cart :addToCart="addToCart" :productsState="productsState">
            </product-cart>
          </div>
        </div>
        <div class="q-mt-lg" v-if="productsState.fetching === true">
          <q-spinner color="primary" size="3em" :thickness="10" />
        </div>
      </div>
    </div>
    <div v-if="productsState.data.data.length > 0" class="col col-md-2">
      <div class="flex flex-center">
        <q-pagination
          v-model="productsState.currentPage"
          :max="productsState.lastPage"
          direction-links
          boundary-links
          icon-first="skip_previous"
          icon-last="skip_next"
          icon-prev="fast_rewind"
          icon-next="fast_forward"
          :disable="productsState.fetching === true"
        />
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
import { defineComponent, onMounted, watchEffect, ref } from "vue";
import { useRoute, useRouter, Router } from "vue-router";
import useProducts from "src/hooks/product";
import useCart from "src/hooks/cart";
import ProductCart from "../components/ProductCart.vue";
import { matShoppingBasket } from "@quasar/extras/material-icons";
export default defineComponent({
  name: "ProductsPage",
  components: { ProductCart },
  setup(props) {
    const route: any = useRoute();
    const pageParam = parseInt(route.params.page);
    const router: Router = useRouter();
    const { productsState, getProducts } = useProducts();
    const { addToCart } = useCart();
    console.log("path=>", route.name);
    onMounted(() => {
      getProducts(route.params.page);
    });
    watchEffect(() => {
      if (
        route.name === "products" &&
        productsState.currentPage !== undefined &&
        productsState.currentPage !== parseInt(route.params.page)
      ) {
        router.push(`/products/${productsState.currentPage}`).then(() => {
          getProducts(parseInt(route.params.page));
        });
      }
    });
    return {
      productsState,
      pageId: route.params.id,
      addToCart,
      matShoppingBasket,
      router,
      pageParam,
      current: ref(3),
      getProducts,
      /* newData: computed(() => {
        return productsState.data.slice(
          (productsState.page - 1) * productsState.totalPage,
          (productsState.page - 1) * productsState.totalPage +
            productsState.totalPage
        );
      }), */
    };
  },
});
</script>
