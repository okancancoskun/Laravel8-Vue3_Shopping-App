import { stat } from "fs";
import { api } from "src/boot/axios";
import { IBaseState, IOrder } from "src/interfaces";
import { reactive } from "vue";
import useLogin from "../auth/userLogin";
import useCart from "../cart";

export interface IState extends IBaseState<IOrder> {}
export interface IOrdersState extends IBaseState<IOrder[]> {}
export interface IPostState extends IBaseState<IOrder> {}

const ordersState = reactive<IOrdersState>({
  fetching: false,
  fetched: false,
  data: <IOrder[]>[],
  error: <any>{},
});
const orderState = reactive<IState>({
  fetching: false,
  fetched: false,
  data: <IOrder>{},
  error: <any>{},
});
const postState = reactive<IPostState>({
  fetching: false,
  fetched: false,
  data: <IOrder>{},
  error: <any>{},
});

type OrderHooksType = {
  createOrder: () => Promise<void>;
  getAll: () => Promise<void>;
  findOne: (id: number) => Promise<void>;
  findOrdersByUserId: () => Promise<void>;
  orderState: IState;
  ordersState: IOrdersState;
  postOrderState: IPostState;
};

export default function useOrder(): OrderHooksType {
  const { user } = useLogin();
  const { resetCart } = useCart();
  const createOrder: () => Promise<void> = async () => {
    postState.fetching = true;
    try {
      const res = await api.post("/order/create");
      if (res.status === 201) {
        postState.fetched = true;
        await resetCart();
      }
    } catch (error) {
      postState.error = error;
    } finally {
      postState.fetching = false;
    }
  };
  const getAll: () => Promise<void> = async () => {
    ordersState.fetching = true;
    try {
      const res = await api.get("/order");
      if (res.status === 200) {
        ordersState.data = res.data as IOrder[];
        ordersState.fetched = true;
      }
    } catch (error) {
      ordersState.error = error;
    } finally {
      ordersState.fetching = true;
    }
  };
  const findOne: (id: number) => Promise<void> = async (id: number) => {
    orderState.fetching = true;
    try {
      const res = await api.get(`/order/${id}`);
      if (res.status === 200) {
        orderState.data = res.data;
        orderState.fetched = true;
      }
    } catch (error) {
      orderState.error === true;
    } finally {
      orderState.fetching = false;
    }
  };
  const findOrdersByUserId: () => Promise<void> = async () => {
    ordersState.fetching = true;
    try {
      const res = await api.get(`/order/findByUserId/${user?.value?.id}`);
      if (res.status === 200) {
        ordersState.data = res.data;
        console.log("sum=>", res.data);
        ordersState.fetched = true;
      }
    } catch (error) {
      ordersState.error = error;
    } finally {
      ordersState.fetching = false;
    }
  };
  return {
    findOne,
    findOrdersByUserId,
    getAll,
    createOrder,
    orderState: orderState,
    ordersState: ordersState,
    postOrderState: postState,
  };
}
