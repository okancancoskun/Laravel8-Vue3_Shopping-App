<template>
  <q-layout view="lHh Lpr lFf">
    <q-header elevated>
      <q-toolbar class="bg-primary text-white shadow-2 rounded-borders">
        <q-btn
          flat
          dense
          round
          icon="menu"
          aria-label="Menu"
          @click="toggleLeftDrawer"
        />
        <q-toolbar-title> Shopping App </q-toolbar-title>
        <q-tabs :style="{ marginRight: '15px' }">
          <q-route-tab to="/" exact replace label="Home" />
          <q-route-tab to="/products/1" exact replace label="Products" />
          <q-route-tab
            v-if="done"
            to="/my-orders"
            exact
            replace
            label="My Orders"
          />
        </q-tabs>
        <div :style="{ marginRight: '15px' }">
          <q-chip
            v-if="done"
            @click="basic = true"
            clickable
            color="primary"
            text-color="white"
          >
            <q-icon size="30" :name="matShoppingBasket" />
            My Basket
            {{ cartState.totalItem > 0 ? `(${cartState.totalItem})` : null }}
          </q-chip>
          <q-chip
            v-if="done"
            @click="triggerLogout"
            clickable
            color="primary"
            text-color="white"
          >
            <q-icon size="30" :name="matLogout" />
            Logout
          </q-chip>
        </div>
      </q-toolbar>
    </q-header>

    <q-drawer v-model="leftDrawerOpen" bordered class="bg-grey-1">
      <q-list>
        <q-item-label header class="text-grey-8"> Side Bar </q-item-label>

        <EssentialLink
          v-for="link in essentialLinks"
          :key="link.title"
          v-bind="link"
        />
      </q-list>
    </q-drawer>

    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
  <q-dialog v-model="basic" transition-show="rotate" transition-hide="rotate">
    <q-card>
      <div :style="{ width: '500px' }">
        <q-card-section>
          <div class="text-h6">My Basket</div>
        </q-card-section>
        <q-card-section v-if="cartState.totalItem === 0">
          Basket is Empty
        </q-card-section>
        <q-card-section class="q-pt-none">
          <div
            class="row q-mb-md"
            v-for="item in cartState.data.cart_items"
            :key="item.id"
          >
            <div class="col-4 row">
              <div class="col-md-6">
                <p class="item-name">
                  {{ item.product_id.name }}
                </p>
              </div>
              <q-separator horizontal />
              <div class="col-md-6">
                <span class="q-ml-lg">price:{{ item.price }}</span>
              </div>
            </div>
            <div class="col-4">
              <q-btn-group
                :style="{
                  width: '20px',
                  height: '35px',
                  marginTop: '-9px',
                  marginLeft: '35px',
                }"
                outline
                v-if="cartState.data.cart_items.length > 0"
              >
                <q-btn
                  @click="decreaseByOne(item.productId)"
                  :style="{ width: '20px' }"
                  outline
                  color="brown"
                  icon="remove"
                  :disabled="item.quantity === 1"
                />
                <q-btn
                  :style="{ width: '20px' }"
                  outline
                  color="brown"
                  :label="item.quantity"
                  disable
                />
                <q-btn
                  :style="{ width: '20px' }"
                  @click="addToCart(item.productId)"
                  outline
                  color="brown"
                  icon="add"
                />
              </q-btn-group>
            </div>
            <div class="col-4">
              <q-btn
                @click="removeItem(item.productId)"
                :style="{ marginTop: '-9px', marginLeft: '20px' }"
                color="red"
                >Remove Item</q-btn
              >
            </div>
          </div>
        </q-card-section>
        <q-card-actions v-if="cartState.totalItem > 0" align="right">
          <q-form @submit.prevent="submitOrder">
            <q-btn
              type="submit"
              flat
              label="Give an Order"
              color="primary"
              v-close-popup
            />
          </q-form>
        </q-card-actions>
      </div>
    </q-card>
  </q-dialog>
</template>
<style>
.item-name {
  white-space: nowrap;
  width: 100px;
  overflow: hidden;
}
</style>
<script lang="ts">
import EssentialLink from "components/EssentialLink.vue";
import {
  matShoppingBasket,
  matAdd,
  matLogout,
} from "@quasar/extras/material-icons";

const linksList = [
  {
    title: "Add Product",
    caption: "",
    icon: "business",
    link: "/#/add-product",
  },
  {
    title: "Add Category",
    caption: "",
    icon: "code",
    link: "/#/add-category",
  },
  {
    title: "Login",
    caption: "",
    icon: "login",
    link: "/#/login",
  },
  {
    title: "Register",
    caption: "",
    icon: "person",
    link: "/#/register",
  },
];

import { defineComponent, onMounted, ref } from "vue";

import { useRouter, Router } from "vue-router";
import useLogin from "src/hooks/auth/userLogin";
import { CartHooksType } from "src/hooks/cart";
import useCart from "src/hooks/cart";
import useOrder from "src/hooks/order/index";
export default defineComponent({
  name: "MainLayout",

  components: {
    EssentialLink,
  },

  setup() {
    const leftDrawerOpen = ref(true);
    const router: Router = useRouter();
    const { done, logout } = useLogin();
    const { getCart, cartState, addToCart, decreaseByOne, removeItem } =
      useCart();
    const { createOrder, postOrderState } = useOrder();
    onMounted(() => {
      getCart();
    });
    const submitOrder = () => {
      createOrder().then(async () => {
        if (postOrderState.fetched) {
          await router.push("/");
        }
      });
    };
    const triggerLogout = () => {
      logout().then(async () => await router.push("/login"));
    };
    return {
      essentialLinks: linksList,
      leftDrawerOpen,
      toggleLeftDrawer() {
        leftDrawerOpen.value = !leftDrawerOpen.value;
      },
      cartState,
      matShoppingBasket,
      basic: ref(false),
      addToCart,
      decreaseByOne,
      removeItem,
      submitOrder,
      matLogout,
      done,
      triggerLogout,
    };
  },
});
</script>
