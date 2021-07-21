import { api } from "src/boot/axios";
import { ICart, ICartItems, IBaseState } from "src/interfaces";
import { reactive, toRefs, Ref, onBeforeMount, onMounted } from "vue";
import useLogin from "../auth/userLogin";

interface IState extends IBaseState<ICart> {
  totalItem: number;
}

export type CartHooksType = {
  addToCart: (productId: number) => Promise<void>;
  getCart: () => Promise<void>;
  decreaseByOne: (productId: number) => Promise<any>;
  removeItem: (productId: number) => Promise<void>;
  resetCart: () => Promise<void>;
  cartState: IState;
};
const state = reactive<IState>({
  fetching: false,
  fetched: false,
  data: <ICart>{},
  totalItem: 0,
  error: <any>{},
});
export default function useCart(): CartHooksType {
  const { user } = useLogin();
  const getCart: () => Promise<void> = async () => {
    state.fetching = true;
    try {
      const res = await api.get(`/cart/getCart/${user?.value?.id}`);
      if (res.status === 200) {
        const totalItem = res.data.cart_items
          .map((item: ICartItems) => item.quantity)
          .reduce((acc: any, value: any) => {
            return acc + value;
          }, 0);
        state.fetched = true;
        state.data = res.data;
        state.totalItem = totalItem ? totalItem : 0;
      }
    } catch (e) {
      state.error = e;
    } finally {
      state.fetching = false;
    }
  };

  const addToCart: (productId: number) => Promise<void> = async (
    productId: number
  ) => {
    const newCart: ICart = state.data;
    state.fetching = true;
    try {
      const res = await api({
        url: "/cart/addItem",
        method: "POST",
        data: {
          productId,
        },
      });
      if (res.status === 201) {
        state.fetched = true;
        const isItemExist: boolean = newCart.cart_items.some(
          (item) => item.productId === productId
        );
        if (!isItemExist) {
          newCart.cart_items.push(res.data);
          console.log("resData =>", res.data);
          state.data = newCart;
        } else {
          const index: number = newCart.cart_items.findIndex(
            (item: ICartItems) => item.productId === res.data.productId
          );
          newCart.cart_items[index].quantity++;
          newCart.cart_items[index].price =
            newCart.cart_items[index].price + res.data.product_id.price;
          state.data = newCart;
        }
        state.totalItem++;
      }
    } catch (error) {
      state.error = error;
    } finally {
      state.fetching = false;
    }
  };

  const decreaseByOne: (productId: number) => Promise<any> = async (
    productId: number
  ) => {
    const newCart: ICart = state.data;
    try {
      const res = await api({
        url: "/cart/decreaseItem",
        method: "POST",
        data: {
          productId,
        },
      });
      if (res.status === 201) {
        const index: number = newCart.cart_items.findIndex(
          (item) => item.productId === productId
        );
        if (newCart.cart_items[index]["quantity"] > 1) {
          newCart.cart_items[index]["quantity"]--;
          newCart.cart_items[index].price =
            newCart.cart_items[index].price -
            newCart.cart_items[index].product_id.price;
          state.totalItem--;
          state.data = newCart;
        } else {
          return { state };
        }
      }
    } catch (error) {
      state.error = error;
    }
  };
  const removeItem: (productId: number) => Promise<void> = async (
    productId: number
  ) => {
    const newCart: ICart = state.data;
    try {
      const res = await api({
        url: "/cart/removeItem",
        method: "POST",
        data: {
          productId,
        },
      });
      if (res.status === 201) {
        const index: number = newCart.cart_items.findIndex(
          (item) => item.productId === productId
        );
        state.totalItem = state.totalItem - newCart.cart_items[index].quantity;
        newCart.cart_items.splice(index, 1);
        state.data = newCart;
      }
    } catch (error) {}
  };
  const resetCart = async (): Promise<void> => {
    state.fetching = false;
    state.fetched = true;
    state.data = <ICart>{};
    state.totalItem = 0;
    state.error = <any>{};
  };
  return {
    addToCart,
    getCart,
    cartState: state,
    decreaseByOne,
    removeItem,
    resetCart,
  };
}
