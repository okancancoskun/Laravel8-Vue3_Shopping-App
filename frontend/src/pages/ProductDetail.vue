<template>
  <div
    v-if="
      Object.keys(productState.data).length > 0 &&
      productState.data.category !== undefined
    "
    class="
      q-pa-md
      row
      items-start
      q-gutter-md
      row
      justify-center
      relative-position
    "
  >
    <q-btn
      v-if="done"
      @click="basic = true"
      :style="{ top: '30px' }"
      class="absolute-top-right"
      >Edit Product</q-btn
    >
    <q-btn
      v-if="done"
      @click="onDelete"
      color="red"
      :style="{ top: '80px' }"
      class="absolute-top-right"
      >Delete Product</q-btn
    >
    <q-card
      class="my-card"
      :style="{ marginTop: '150px', width: '500px' }"
      flat
      bordered
    >
      <q-card-section horizontal>
        <q-card-section :style="{ width: '700px' }" vertical>
          <q-card-section>
            <b>Product Name: </b> {{ productState.data.name }}
          </q-card-section>
          <q-separator horizontal />
          <q-card-section>
            <b>Detail: </b> {{ productState.data.detail }}
          </q-card-section>
          <q-separator horizontal />
          <q-card-section>
            <b>Price: </b> {{ productState.data.price }}$
          </q-card-section>
          <q-card-actions v-if="done" class="row justify-center">
            <q-btn
              size="md"
              :style="{ width: '150px' }"
              @click="addToCart(productState.data.id)"
              outline
              color="primary"
              :disabled="cartState.fetching"
            >
              {{ cartState.fetching === false ? "ADD TO CART" : null }}
              <div class="row" v-if="cartState.fetching">
                <q-spinner color="primary" size="1em" :thickness="10" />
              </div>
            </q-btn>
          </q-card-actions>
        </q-card-section>
        <q-img
          class="col-5"
          :style="{ width: '300px' }"
          src="https://cdn.quasar.dev/img/parallax2.jpg"
        />
        <q-separator horizontal />
      </q-card-section>
    </q-card>
  </div>
  <q-dialog v-model="basic" transition-show="rotate" transition-hide="rotate">
    <q-card :style="{ width: '500px', height: '700px' }">
      <div class="row justify-center">
        <q-form @submit.prevent="onSubmit" class="q-gutter-md q-mt-lg">
          <q-input
            v-model="updateBody.name"
            label="Product Name *"
            type="text"
            id="update_prd_name"
            lazy-rules
            :style="{ width: '300px' }"
            :rules="[
              (val) => (val && val.length > 0) || 'Please type something',
            ]"
          />

          <q-input
            filled
            type="textarea"
            label="Product Detail"
            v-model="updateBody.detail"
            lazy-rules
            :style="{ width: '300px' }"
            :rules="[
              (val) => (val && val.length > 0) || 'Please type something',
            ]"
          />
          <q-input
            filled
            type="number"
            label="Product Price"
            v-model="updateBody.price"
            lazy-rules
            :style="{ width: '300px' }"
          />
          <q-select
            v-model="updateBody.categoryId"
            :options="selectOptions"
            filled
            label="Choose Category"
            :style="{ width: '300px' }"
            map-options
            emit-value
          />
          <div>
            <q-btn label="Submit" type="submit" color="primary" />
          </div>
        </q-form>
      </div>
    </q-card>
  </q-dialog>
</template>
<style>
.detail-card {
  width: 100%;
}
</style>
<script lang="ts">
import {
  defineComponent,
  onMounted,
  ref,
  computed,
  reactive,
  onBeforeMount,
  ComputedRef,
} from "vue";
import useCategory from "../hooks/category/index";

import { useRoute, useRouter, Router } from "vue-router";
import useProducts from "src/hooks/product";
import useLogin from "src/hooks/auth/userLogin";
import useCart from "src/hooks/cart";
import { matShoppingBasket } from "@quasar/extras/material-icons";
import { IUpdateProduct } from "src/interfaces";
export default defineComponent({
  name: "ProductDetailPage",
  setup(props) {
    const route: any = useRoute();
    const router: Router = useRouter();
    const {
      productState,
      getProduct,
      updateProduct,
      postProductState,
      deleteProduct,
    } = useProducts();
    const { getCategories, categoryState } = useCategory();
    const { addToCart, cartState } = useCart();
    const { done } = useLogin();
    onMounted(async () => {
      getProduct(route.params.id);
      getCategories();
    });
    const updateBody: ComputedRef<IUpdateProduct> = computed(() => {
      return reactive<IUpdateProduct>({
        name: productState.data.name,
        detail: productState.data.detail,
        price: productState.data.price,
        categoryId: productState.data.category.id,
      });
    });
    const onDelete = () => {
      deleteProduct(productState.data.id).then(async () => {
        if (postProductState.fetched) {
          await router.push("/products/1");
        }
      });
    };
    const onSubmit = () => {
      updateProduct(updateBody.value, productState.data.id).then(async () => {
        if (postProductState.fetched) {
          window.location.reload();
        }
      });
    };
    return {
      productState,
      pageId: route.params.id,
      addToCart,
      cartState,
      matShoppingBasket,
      router,
      getProduct,
      basic: ref(false),
      selectOptions: computed(() =>
        categoryState.data.map((item: any) => {
          return {
            label: item.name,
            value: item.id,
          };
        })
      ),
      categoryState,
      onSubmit,
      updateBody,
      onDelete,
      done,
    };
  },
});
</script>
